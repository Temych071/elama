<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Module\Notification\TelegramNotificationService;

class UnsetTelegramWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:unset_telegram_webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unset telegram webhook';

    /**
     * Execute the console command.
     */
    public function handle(TelegramNotificationService $service): int
    {
        $result = $service->bot->deleteWebhook();
        echo $result->getDescription() . PHP_EOL;

        return 0;
    }
}
