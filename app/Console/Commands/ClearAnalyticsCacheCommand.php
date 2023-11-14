<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearAnalyticsCacheCommand extends Command
{
    protected $signature = 'analytics:cache-clear {--project=}';
    protected $description = 'Clears analytics cache for all projects.';

    public function handle(): int
    {
        // В доках написано про упорядоченность массива тэгов
        // На всякий случай сделал в том же порядк

        $tags = [];

        if ($this->hasArgument('project')) {
            $tags[] = 'project:' . $this->argument('project');
        }

        $tags[] = 'analytics';

        if (Cache::tags($tags)->flush()) {
            $this->info('Cache with tags [' . implode(', ', $tags) . '] cleared.');
        } else {
            $this->info('Something went wrong...');
        }

        return 0;
    }
}
