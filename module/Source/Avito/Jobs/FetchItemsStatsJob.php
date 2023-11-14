<?php

declare(strict_types=1);

namespace Module\Source\Avito\Jobs;

use App\Infrastructure\DateRange;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Module\Source\Avito\Actions\Fetch\FetchItemsStatsAction;
use Module\Source\Sources\Models\Source;

final class FetchItemsStatsJob implements ShouldBeUnique, ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $uniqueFor = 216_000;

    public function __construct(public int $sourceId, public DateRange $dateRange)
    {
        $this->onQueue('avito');
    }

    public function handle(): void
    {
        /** @noinspection PhpParamsInspection */
        app(FetchItemsStatsAction::class)
            ->execute(Source::query()->findOrFail($this->sourceId), $this->dateRange);
    }

    public function uniqueId(): string
    {
        return (string)$this->sourceId;
    }
}
