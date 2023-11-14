<?php

declare(strict_types=1);

namespace Module\Billing\Payments\Services;

use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Module\Billing\Account\Enums\TransactionType;
use Module\Billing\Payments\Exceptions\AutoPaymentNotPaidException;
use Module\Billing\Payments\Models\Payment;
use Module\Billing\Payments\Models\PaymentMethod;
use Module\User\Models\User;

final class YooKassaService
{
    public const API_URL = 'https://api.yookassa.ru/v3/';

    protected const DEFAULT_CURRENCY = 'RUB';

    protected int $market_id;
    protected string $market_secret;
    protected string $latest_idempotence_key;

    public function __construct()
    {
        $this->market_id = (int)config('billing.yoo-kassa.id');
        $this->market_secret = config('billing.yoo-kassa.secret');
    }

    /**
     * @throws BusinessException
     */
    public function paymentCreateUrl(
        User $user,
        float|int $amount,
        bool $saveMethod = false,
        array $metadata = []
    ): string {
        $q = [
            'amount' => [
                'value' => (string)(float)$amount,
                'currency' => self::DEFAULT_CURRENCY,
            ],
            'capture' => true,
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => route('user.billing.payment-redirect.show'),
            ],
            'description' => "Пополнение счёта на сумму $amount рублей.",
            'save_payment_method' => $saveMethod,
            'metadata' => [
                ...$metadata,
                'user_id' => (string)$user->id,
            ],
        ];

        $res = $this->sendNewPayment($user, $q);
        $this->createPaymentFromResponse($user, $res);

        return $res['confirmation']['confirmation_url'];
    }

    /**
     * @throws AutoPaymentNotPaidException
     */
    public function payBySavedMethod(
        User $user,
        string $paymentMethodId,
        float|int $amount,
        array $metadata = [],
    ): void {
        $q = [
            'amount' => [
                'value' => (string)(float)$amount,
                'currency' => self::DEFAULT_CURRENCY,
            ],
            'capture' => true,
            'description' => "Пополнение счёта на сумму $amount рублей.",
            'payment_method_id' => $paymentMethodId,
            'metadata' => [
                ...$metadata,
                'user_id' => (string)$user->id,
            ],
        ];

        $res = $this->sendNewPayment($user, $q);
        $payment = $this->createPaymentFromResponse($user, $res);
        if ($payment->paid) {
            $payment->makeTransaction(TransactionType::AUTO_REFILL);
        } else {
            throw new AutoPaymentNotPaidException();
        }
    }

    private function sendNewPayment(User $user, array $query): array
    {
        $res = $this->post('payments', $query);

        if ((int)$res['metadata']['user_id'] !== $user->id) {
            throw new BusinessException('Платёжный сервис вернул неверный индекс пользователя.');
        }

        return $res;
    }

    private function createPaymentFromResponse(User $user, array $res): Payment
    {
        /** @var Payment $payment */
        $payment = $user->payments()->create([
            'payment_id' => $res['id'],
            'status' => $res['status'],
            'paid' => $res['paid'],
            'amount' => $res['amount']['value'],
            'currency' => $res['amount']['currency'],
            'idempotence_key' => $this->latest_idempotence_key,
        ]);

        return $payment;
    }

    protected function post(string $path, mixed $data = [], array $headers = []): mixed
    {
        return Http::withHeaders($this->getHeaders($headers))
            ->asJson()
            ->withBasicAuth((string)$this->market_id, $this->market_secret)
            ->post(self::API_URL . $path, $data)
            ->throw()
            ->json();
    }

    protected function getHeaders(array $custom = []): array
    {
        return array_merge([
            'Content-Type' => 'application/json',
            'Idempotence-Key' => $this->genIdempotenceKey(),
        ], $custom);
    }

    protected function genIdempotenceKey(): string
    {
        $this->latest_idempotence_key = Str::uuid()->toString();
        return $this->latest_idempotence_key;
    }
}
