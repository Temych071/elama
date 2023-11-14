<?php

namespace App\Http\Controllers\Telegram;

use Module\Notification\TelegramNotificationService;

class TelegramWebhookController
{
    public function __construct(private readonly TelegramNotificationService $service)
    {
    }

    public function __invoke(): void
    {
        $bot = $this->service->bot;
        // Enable admin users
        $bot->enableAdmins(config('telegram.admins'));
        $bot->addCommandsPaths(config('telegram.commands.paths'));
//            $bot->enableMySql($config['mysql_credentials']);
        $bot->enableLimiter(config('telegram.limiter'));
        $bot->handle();
    }
}
