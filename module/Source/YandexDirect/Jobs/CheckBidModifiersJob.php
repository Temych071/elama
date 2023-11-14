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
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Actions\CheckBidModifiersAction;

final class CheckBidModifiersJob implements ShouldQueue, ShouldBeUnique
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
    ) {
        $this->onQueue('direct');
    }

    public function handle(): void
    {
        /** @var Source $source */
        $source = Source::query()->findOrFail($this->sourceId);

        app(CheckBidModifiersAction::class)
            ->execute($source);
    }

    public function uniqueId(): string
    {
        return "s{$this->sourceId}";
    }
}
