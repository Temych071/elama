<?php

declare(strict_types=1);

namespace Module\Source\Avito\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Module\Source\Avito\Actions\Fetch\FetchItemsAction;
use Module\Source\Sources\Models\Source;
use Throwable;

final class FetchItemsJob implements ShouldBeUnique, ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $uniqueFor = 216_000;
    public $tries = 1;
    public function __construct(public int $sourceId, public int $page = 1)
    {
        $this->onQueue('avito');
    }

    public function handle(): void
    {
        $log = Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/avito-jobs.log'),
        ]);
        $log->debug("FetchItemsJob($this->sourceId, $this->page): Start handle.");

        /** @var Source $source */
        $source = Source::query()->findOrFail($this->sourceId);

        $log->debug("FetchItemsJob($this->sourceId, $this->page): Source found.");

        $nextPage = app(FetchItemsAction::class)->execute($source, $this->page);
        $log->debug("FetchItemsJob($this->sourceId, $this->page): After action.");

        if ($nextPage) {
            $log->debug("FetchItemsJob($this->sourceId, $this->page): Dispatch for next page.");
            $this->prependToChain(new self($this->sourceId, $nextPage))->delay(3);
        }

        $log->debug("FetchItemsJob($this->sourceId, $this->page): End handle.");
    }

    public function uniqueId(): string
    {
        return (string)$this->sourceId;
    }

    public function failed(Throwable $exception): void
    {
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/avito-jobs.log'),
        ])->debug("FetchItemsJob($this->sourceId, $this->page): Error '{$exception->getMessage()}'.");
    }
}
