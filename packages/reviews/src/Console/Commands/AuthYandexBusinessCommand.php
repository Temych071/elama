<?php

namespace Reviews\Console\Commands;

use Illuminate\Console\Command;
use Reviews\Parsers\Yandex\Services\YandexSessionService;

class AuthYandexBusinessCommand extends Command
{
    protected $signature = 'reviews:yandex-business-auth {--force}';
    protected $description = 'Auth in Yandex.Business and save session.';

    public function handle(): void
    {
        $service = app(YandexSessionService::class);

        if ($this->option('force')) {
            $service->resetSession();
            $this->info("Session was reset.");
        }

        $sessionCookie = $service->getSession();

        $this->info("Session created.");
        $this->info("New session cookie: $sessionCookie");
    }
}
