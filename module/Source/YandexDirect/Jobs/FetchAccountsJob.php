<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Module\Campaign\Models\Campaign;
use Module\Source\YandexDirect\Actions\Fetch\FetchAccountsAction;

final class FetchAccountsJob implements ShouldQueue, ShouldBeUnique
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
        public int $campaignId,
    ) {
        $this->onQueue('direct');
    }

    public function handle(): void
    {
        app(FetchAccountsAction::class)
            ->execute(Campaign::query()->findOrFail($this->campaignId));
    }

    public function uniqueId(): string
    {
        return (string)$this->campaignId;
    }
}
