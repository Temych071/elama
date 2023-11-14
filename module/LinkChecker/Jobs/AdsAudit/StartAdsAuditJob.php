<?php

declare(strict_types=1);

namespace Module\LinkChecker\Jobs\AdsAudit;

use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Module\LinkChecker\Jobs\CrawlInternalLinksJob;
use Module\LinkChecker\Models\SeoAdsAudit;
use Module\LinkChecker\Providers\LinkProvider;
use Throwable;

class StartAdsAuditJob implements ShouldQueue //, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly int $sourceId,
        public readonly LinkProvider $linkProvider,
    ) {
        $this->onQueue('link-checks');
    }

    public function handle(): void
    {
        $checks = $this->linkProvider->getLinkList($this->sourceId);

        if (empty($checks)) {
            return;
        }

        $model = SeoAdsAudit::startAudit($this->sourceId, $checks);

        $jobs = array_map(static fn ($check): array => [
            new AuditAdsJob($model, $check->url),
            new CrawlInternalLinksJob($check->url),
        ], $checks);

        $batch = Bus::batch($jobs)
            ->allowFailures()
            ->catch(function (Batch $batch, Throwable $e) use ($model): void {
                $model->update(['status' => SeoAdsAudit::STATUS_ERROR]);
            })->finally(function (Batch $batch) use ($model): void {
                $model->update(['status' => SeoAdsAudit::STATUS_COMPLETED]);
            })
            ->dispatch();

        $model->update(['batch_id' => $batch->id]);
    }
}
