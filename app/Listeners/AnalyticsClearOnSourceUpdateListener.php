<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Support\Facades\Cache;
use Module\Source\Sources\Events\SourceUpdateFinishedEvent;

final class AnalyticsClearOnSourceUpdateListener
{
    public function handle(SourceUpdateFinishedEvent $event): void
    {
        $project = $event->source->campaign;

        Cache::tags(['project:' . $project->id, 'analytics'])->flush();
    }
}
