<?php

declare(strict_types=1);

namespace Reviews\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Reviews\Parsers\Yandex\Services\YandexSessionService;

final class SetYandexBusinessSessionCommand extends Command
{
    protected $signature = 'reviews:set-yandex-session {session-id}';
    protected $description = 'Set yandex business session id manually.';

    public function handle(): void
    {
        Cache::put(YandexSessionService::SESSION_ID_CACHE_KEY, $this->argument('session-id'));
    }
}
