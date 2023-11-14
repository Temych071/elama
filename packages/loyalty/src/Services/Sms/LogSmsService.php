<?php

declare(strict_types=1);

namespace Loyalty\Services\Sms;

use Illuminate\Support\Facades\Log;
use Loyalty\Contracts\SmsSenderService;

final class LogSmsService implements SmsSenderService
{
    public function send(string $phone, string $message): void
    {
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/loyalty-sms.log'),
        ])->info("SMS to $phone: $message");
    }
}
