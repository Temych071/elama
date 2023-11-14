<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Module\Notification\TelegramNotificationService;

class SetTelegramWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:set_telegram_webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set telegram webhook';

    /**
     * Execute the console command.
     */
    public function handle(TelegramNotificationService $service): int
    {
        $result = $service->bot->setWebhook(config('telegram.webhook.url'));

        echo $result->getDescription() . PHP_EOL;

        return 0;
    }
}
