<?php

declare(strict_types=1);

namespace Module\LinkChecker\Jobs\AdsAudit;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Module\LinkChecker\Actions\GetPageSpeedInsightsAction;
use Module\LinkChecker\Actions\GetPageStateAction;
use Module\LinkChecker\Models\SeoAdsAudit;
use Module\LinkChecker\Models\SeoAudit;

class AuditAdsJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly SeoAdsAudit $seoAdsAudit,
        public readonly string $url,
    ) {
    }

    public function handle(GetPageSpeedInsightsAction $getPageSpeedInsights, GetPageStateAction $getPageStateAction): void
    {
        DB::transaction(function () use ($getPageStateAction, $getPageSpeedInsights): void {
            $seoAudit = SeoAudit::findOrCreateByLink($this->url);

            if (!$seoAudit->isUpdateAvailable()) {
                return;
            }

            $audits = $getPageSpeedInsights->execute($seoAudit->link);
            $pageState = $getPageStateAction->execute($seoAudit->link);

            $seoAudit->saveResult($audits, $pageState);

            $this->seoAdsAudit->seoAudits()->attach($seoAudit);
        });
    }
}
