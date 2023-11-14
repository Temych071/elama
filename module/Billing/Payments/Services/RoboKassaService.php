<?php

declare(strict_types=1);

namespace Module\Billing\Payments\Services;

use App\Exceptions\BusinessException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Module\Billing\Account\Enums\TransactionType;
use Module\Billing\Payments\Exceptions\AutoPaymentNotPaidException;
use Module\Billing\Payments\Exceptions\RobokassaApiError;
use Module\Billing\Payments\Models\Payment;
use Module\Billing\Payments\Models\PaymentMethod;
use Module\User\Models\User;

final class RoboKassaService
{
    protected const DEFAULT_CURRENCY = 'RUB';

    protected string $merchantId;
    protected string $password1;
    protected string $password2;
    protected bool $test_mode = false;

    public function __construct()
    {
        $this->merchantId = (string) config('billing.robokassa.merchant-id');
        $this->password1 = (string) config('billing.robokassa.password-1');
        $this->password2 = (string) config('billing.robokassa.password-2');
        $this->test_mode = (bool) config('billing.robokassa.text_mode', false);
    }

    public function paymentCreateUrl(
        User $user,
        int $amount,
        bool $saveMethod = false,
        array $metadata = []
    ): string {
        $payment = $this->createPayment($user, $amount);

        $metaItems = [
            ...$metadata,
            'user_id' => $user->id,
            'recurring' => $saveMethod ? 1 : 0,
        ];

        $post = [
            'MerchantLogin' => $this->merchantId,
            'OutSum' => $amount,
            'Description' => "Пополнение счёта на сумму $amount рублей.",
            'IsTest' => $this->test_mode ? 1 : 0,
            'InvId' => $payment->id,
            'SignatureValue' => $this->makeSign1($amount, $payment->id, $metaItems),
            'Recurring' => $saveMethod ? 'true' : 'false',
            'Culture' => 'ru',
            ...self::prepareMetadata($metaItems),
        ];

        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/robokassa-results.log'),
        ])->debug("Robokassa payment params", ['post' => $post]);

        $res = $this->sendNewPayment($post);

        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/robokassa-results.log'),
        ])->debug("Robokassa new payment", ['res' => $res]);

        $payment->invoice_uuid = $res['invoiceID'];
        $payment->save();

        return 'https://auth.robokassa.ru/Merchant/Index/'.$res['invoiceID'];
    }

    public function payBySavedMethod(
        User $user,
        string $paymentMethodId,
        float|int $amount,
        array $metadata = [],
    ): void {
        $payment = $this->createPayment($user, $amount);

        $metaItems = [
            ...$metadata,
            'user_id' => $user->id,
        ];

        $post = [
            'MerchantLogin' => $this->merchantId,
            'OutSum' => $amount,
            'Description' => "Пополнение счёта на сумму $amount рублей.",
            'IsTest' => $this->test_mode ? 1 : 0,
            'InvId' => $payment->id,
            'SignatureValue' => $this->makeSign1($amount, $payment->id, $metaItems),
//            'Recurring' => 'true',
            'PreviousInvoiceID' => $paymentMethodId,
            ...self::prepareMetadata($metaItems),
        ];

        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/robokassa-results.log'),
        ])->debug("payBySavedMethod body", ['post' => $post]);

        $res = $this->sendRecurrentPayment($post);

        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/robokassa-results.log'),
        ])->debug("payBySavedMethod res", ['res' => $res]);

//        $payment->invoice_uuid = $res['invoiceID'];
//        $payment->save();
    }

    public function makeSign1(float $amount, int $inv_id, array $meta = []): string
    {
        return md5("$this->merchantId:$amount:$inv_id:$this->password1:".self::prepareMetadataForSign($meta));
    }

    public function makeSign2(int $amount, int $inv_id, array $meta = []): string
    {
        return md5("$this->merchantId:$amount:$inv_id:$this->password2:".self::prepareMetadataForSign($meta));
    }

    public static function extractMetadata(array $data): array
    {
        $keys = array_filter(array_keys($data), static fn(string $key) => Str::startsWith($key, 'Shp_'));
        sort($keys);
        $res = [];
        foreach ($keys as $key) {
            $res[Str::after($key, 'Shp_')] = $data[$key];
        }

        return $res;
    }

    public static function prepareMetadata(array $metadata): array
    {
        $res = [];
        $keys = array_keys($metadata);
        sort($keys);
        foreach ($keys as $key) {
            $res[Str::startsWith($key, 'Shp_') ? $key : "Shp_$key"] = $metadata[$key];
        }

        return $res;
    }

    public static function prepareMetadataForSign(array $metadata): string
    {
        $res = [];
        foreach (self::prepareMetadata($metadata) as $key => $val) {
            $res[] = "$key=$val";
        }

        return implode(':', $res);
    }

    private function sendNewPayment(array $query): array
    {
        return $this->post('https://auth.robokassa.ru/Merchant/Indexjson.aspx', $query);
    }

    private function sendRecurrentPayment(array $query): string
    {
        return $this->post('https://auth.robokassa.ru/Merchant/Recurring', $query);
    }

    public function createPayment(User $user, float $amount): Payment
    {
        /** @var Payment $payment */
        $payment = $user->payments()->create([
            'amount' => $amount,
        ]);

        return $payment;
    }

    protected function post(string $url, mixed $data = []): mixed
    {
        $body = [];
        foreach ($data as $key => $val) {
            $body[] = "$key=".urlencode((string) $val);
        }
        $body = implode('&', $body);

        $req = new PendingRequest();
        $req->withBody($body, 'application/x-www-form-urlencoded');
        $req->throw();

        $res = $req->post($url);
        $res = $res->json() ?? $res->body();

        if (is_string($res) && Str::startsWith($res, 'ERROR')) {
            throw new RobokassaApiError($res);
        }

        return $res;
    }
}
