<?php

declare(strict_types=1);

namespace Loyalty\Services\Sms;

use Error;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Loyalty\Contracts\SmsSenderService;
use Loyalty\Exceptions\ProstoSmsApiError;

final class ProstoSmsService implements SmsSenderService
{
    protected const API_URL = 'http://api.sms-prosto.ru/';

    protected readonly bool $useApiKey;
    protected readonly string $apiKey;
    protected readonly string $senderName;

    public function __construct()
    {
        $this->senderName = config('services.prosto-sms.sender');
        if (strlen($this->senderName) > 11) {
            throw new Error('ProstoSMS: Sender name\'s len must be <=11.');
        }

        if (!empty(config('services.prosto-sms.api_key'))) {
            $this->useApiKey = true;
            $this->apiKey = config('services.prosto-sms.api_key');
            return;
        }


        if (!empty(config('services.prosto-sms.email')) && !empty(config('services.prosto-sms.password'))) {
            $this->useApiKey = false;
            $this->apiKey = config('services.prosto-sms.email').':'.config('services.prosto-sms.password');
            return;
        }

        throw new Error('ProstoSMS: API key not specified.');
    }


    public function send(string $phone, string $message): void
    {
        $res = $this->get([
            'phone' => $phone,
            'text' => $message,
        ]);

        if ($res['msg']['type'] === 'error') {
            throw new ProstoSmsApiError($res['msg']['text'], (int) $res['msg']['err_code']);
        }
    }

    protected function get(array $params): array
    {
        $query = [
            'format' => 'json',
            'method' => 'push_msg',
            'sender_name' => $this->senderName,
            ...$params,
            ...$this->getAuthParams(),
        ];
        // Вообще, в доке написано, что и POST можно, но почему-то не работает...
        $res = Http::get(self::API_URL, $query)->throw()->json();
        return $res['response'];
    }

    protected function getAuthParams(): array
    {
        return $this->useApiKey ? [
            'key' => $this->apiKey,
        ] : [
            'email' => Str::before($this->apiKey, ':'),
            'password' => Str::after($this->apiKey, ':'),
        ];
    }
}
