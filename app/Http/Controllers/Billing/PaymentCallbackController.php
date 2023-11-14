<?php

declare(strict_types=1);

namespace App\Http\Controllers\Billing;

use App\Exceptions\BusinessException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Module\Billing\Payments\Enums\PaymentMethodStatus;
use Module\Billing\Payments\Helpers\RobokassaSecurityHelper;
use Module\Billing\Payments\Helpers\YooKassaSecurityHelper;
use Module\Billing\Payments\Models\DiscountCode;
use Module\Billing\Payments\Models\Payment;
use Module\Billing\Payments\Models\PaymentMethod;
use Module\Billing\Payments\Services\RoboKassaService;
use Throwable;
use Module\User\Models\User;

final class PaymentCallbackController
{
    /**
     * @throws Throwable
     */
    public function __invoke(Request $request, RobokassaSecurityHelper $securityHelper): string
    {
//        if (!$securityHelper->isIPTrusted($request->ip())) {
//            throw new BusinessException("Request from non-trusted ip ({$request->ip()}).");
//        }

        $data = $request->all();

        if (empty($data)) {
            throw new BusinessException("Invalid payment callback request.");
        }

        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/robokassa-results.log'),
        ])->debug("PaymentCallbackController(): Result", ['data' => $data]);

        $service = app(RoboKassaService::class);

        $sign = $service->makeSign2(
            (int) $data['OutSum'],
            (int) $data['InvId'],
            RoboKassaService::extractMetadata($data),
        );

        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/robokassa-results.log'),
        ])->debug("PaymentCallbackController(): sign", ['$sign' => $sign, 'SignatureValue' => $data['SignatureValue']]);

        if (!strcasecmp($data['SignatureValue'], $sign)) {
            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/robokassa-results.log'),
            ])->debug("PaymentCallbackController(): Invalid sign",
                ['expected' => $sign, 'given' => $data['SignatureValue']]);
            throw new BusinessException("Invalid sign.");
        }

        /** @var User $user */
        $user = User::query()->findOrFail($data['Shp_user_id']);

        /** @var Payment $payment */
        $payment = Payment::query()
            ->whereNull('paid_at')
            ->where('user_id', $user->id)
            ->where('id', $data['InvId'])
            ->firstOrFail();

        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/robokassa-results.log'),
        ])->debug("PaymentCallbackController(): payment", ['$payment' => $payment]);

        $payment->paid_at = now();

        DB::transaction(static function () use ($data, $payment): void {
            $payment->makeTransaction();
            $payment->save();

            if (!empty($data['Shp_discount_code'] ?? null)) {
                DiscountCode::query()
                    ->where('id', $data['Shp_discount_code'])
                    ->available()
                    ->first()
                    ?->makeTransactions($payment)
                    ?->markAsUsed();
            }
        });

        if (!empty($data['Shp_recurring'])) {
            // Игнор всего, кроме банковских карт, т.к. только их можно сохранять,
            // а касса никак не даёт понять, что метод действительно сохранён
            if (Str::contains($data['IncCurrLabel'], 'BankCard')) {
                $user->paymentMethods()
                    ->create([
                        'name' => $data['IncCurrLabel'] ?? 'Unnamed',
                        'method_id' => $payment->id,
                        'status' => PaymentMethodStatus::AVAILABLE,
                    ]);
            }
        }

        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/robokassa-results.log'),
        ])->debug("PaymentCallbackController(): OK");

        return "OK$payment->id";
    }
}
