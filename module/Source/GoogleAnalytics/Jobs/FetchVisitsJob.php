<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Module\Source\GoogleAnalytics\Actions\Fetch\FetchVisitsAction;
use Module\Source\Sources\Models\Source;

final class FetchVisitsJob implements ShouldQueue, ShouldBeUnique
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The number of seconds after which the job's unique lock will be released.
     */
    public int $uniqueFor = 216_000; // 1 hour

    public function __construct(
        public Source $source,
        public Carbon $fromDate,
    ) {
        $this->onQueue('analytics');
    }

    public function handle(): void
    {
        app(FetchVisitsAction::class)
            ->execute($this->source, $this->fromDate);
    }

    public function uniqueId(): string
    {
        return (string)$this->source->id;
    }
}
