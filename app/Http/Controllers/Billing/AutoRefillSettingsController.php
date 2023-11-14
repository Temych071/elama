<?php

namespace App\Http\Controllers\Billing;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Module\Billing\Payments\Enums\PaymentMethodStatus;
use Module\Billing\Payments\Models\PaymentMethod;
use Module\User\Actions\GetUserAutoRefillSettingsAction;
use Module\User\DTO\AutoRefillSettings;
use Module\User\Models\User;

class AutoRefillSettingsController
{
    public function getPaymentMethods(Request $request): array
    {
        /** @var User $user */
        $user = $request->user();
        return $user->paymentMethods->transform(static fn(PaymentMethod $paymentMethod) => [
            'name' => str_replace([
                'BankCardPSR',
                'BankCard',
            ], [
                'Пластиковая карта',
                'Пластиковая карта',
            ], $paymentMethod->name),
            'id' => $paymentMethod->id,
            'status' => $paymentMethod->status->value,
            'created_at' => $paymentMethod->created_at,
        ])->toArray();
    }

    public function getSettings(Request $request): AutoRefillSettings
    {
        /** @var User $user */
        $user = $request->user();

        return app(GetUserAutoRefillSettingsAction::class)->execute($user);
    }

    public function saveSettings(Request $request): AutoRefillSettings
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validate([
            'enabled' => ['required', 'boolean'],
            'amount' => ['required', 'integer', 'min:2'],
            'limit' => ['required', 'integer', 'min:2'],
            'payment_method_id' => [
                'nullable',
                'required_if:enabled,true',
                'exclude_if:enabled,false',
                'integer',
                Rule::exists('billing_payment_methods', 'id')
                    ->where('status', PaymentMethodStatus::AVAILABLE->value)
                    ->where('user_id', $user->id),
            ],
        ]);

        $user->auto_refill_settings = $data;
        $user->save();

        return $user->auto_refill_settings;
    }

    public function deletePaymentMethod(Request $request): void
    {
        /** @var User $user */
        $user = $request->user();

        $paymentMethodId = $request->validate([
            'payment_method_id' => ['required', 'integer'],
        ])['payment_method_id'];

        if ($user->auto_refill_settings?->payment_method_id === $paymentMethodId) {
            $settings = $user->auto_refill_settings;
            $settings->payment_method_id = null;
            $user->auto_refill_settings = $settings;
            $user->save();
        }

        $user
            ->paymentMethods()
            ->findOrFail($paymentMethodId)
            ?->forceDelete();
    }
}
