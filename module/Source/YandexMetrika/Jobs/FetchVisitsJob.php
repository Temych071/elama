<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexMetrika\Actions\Fetch\FetchVisitsAction;

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
        public int $sourceId,
        public Carbon $fromDate,
    ) {
        $this->onQueue('metrika');
    }

    public function handle(): void
    {
        app(FetchVisitsAction::class)
            ->execute(Source::query()->findOrFail($this->sourceId), $this->fromDate);
    }

    public function uniqueId(): string
    {
        return (string)$this->sourceId;
    }
}
