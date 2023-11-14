<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Jobs;

use App\Infrastructure\DateRange;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Module\Campaign\Models\Campaign;
use Module\Source\YandexDirect\Actions\Fetch\FetchReportsAction;

final class FetchReportsJob implements ShouldQueue, ShouldBeUnique
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 5;

    /**
     * The number of seconds after which the job's unique lock will be released.
     */
    public int $uniqueFor = 216_000; // 1 hour

    private const WAIT_REPORT_DELAY = 20;

    public function __construct(
        public int $campaignId,
        public DateRange $dateRange,
        public string $reportName,
    ) {
        $this->onQueue('direct');
    }

    public function handle(): void
    {
        $res = app(FetchReportsAction::class)
            ->execute(Campaign::query()->findOrFail($this->campaignId), $this->dateRange, $this->reportName);

        if (!$res) {
            $this->release(self::WAIT_REPORT_DELAY * $this->attempts());
        }
    }

    public function uniqueId(): string
    {
        return (string)$this->campaignId;
    }
}
