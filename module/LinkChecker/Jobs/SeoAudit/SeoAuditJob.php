<?php

declare(strict_types=1);

namespace Module\LinkChecker\Jobs\SeoAudit;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Module\LinkChecker\Actions\GetPageSpeedInsightsAction;
use Module\LinkChecker\Actions\GetPageStateAction;
use Module\LinkChecker\Models\SeoAudit;
use Throwable;

final class SeoAuditJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $auditResultUuid,
    ) {
        $this->onQueue('seo');
    }

    public function handle(): void
    {
        $model = SeoAudit::query()
            ->where('uuid', $this->auditResultUuid)
            ->firstOrFail();

        if ($model->data_updated_at && $model->data_updated_at->diffInMinutes() < 10) {
            return;
        }

        $audits = app(GetPageSpeedInsightsAction::class)->execute($model->link);
        $pageState = app(GetPageStateAction::class)->execute($model->link);

        $model->saveResult($audits, $pageState);
    }

    public function failed(Throwable $exception): void
    {
        SeoAudit::query()
            ->where('uuid', $this->auditResultUuid)
            ->firstOrFail()
            ->saveError();
    }
}
