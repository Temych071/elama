<?php

namespace App\Console;

use App\Console\Commands\CleanupSourceStatisticsCommand;
use App\Console\Commands\DispatchSourcesAutoUpdateCommand;
use App\Console\Commands\PaySubscriptionCommand;
use App\Console\Commands\RunAutoRefillCommand;
use App\Console\Commands\StartLinkCheckerCommand;
use Illuminate\Cache\Console\PruneStaleTagsCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Reviews\Console\Commands\FetchExternalReviewsCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     */
    protected $commands = [
        DispatchSourcesAutoUpdateCommand::class,
        StartLinkCheckerCommand::class,
        PaySubscriptionCommand::class,
        CleanupSourceStatisticsCommand::class,
        RunAutoRefillCommand::class,
        PruneStaleTagsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(DispatchSourcesAutoUpdateCommand::class)->everyFifteenMinutes();
        $schedule->command(PaySubscriptionCommand::class)->everyFifteenMinutes();
        $schedule->command(StartLinkCheckerCommand::class)->daily();
//        $schedule->command(ClearAnalyticsCacheCommand::class)->daily();
        $schedule->command(FetchExternalReviewsCommand::class)->dailyAt('00:00');
        $schedule->command('cache:prune-stale-tags')->hourly();
        $schedule->command('queue:prune-batches')->daily();
		
		
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
