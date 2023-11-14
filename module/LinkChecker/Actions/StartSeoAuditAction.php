<?php

declare(strict_types=1);

namespace Module\LinkChecker\Actions;

use Illuminate\Support\Facades\DB;
use Module\LinkChecker\Jobs\SeoAudit\SeoAuditJob;
use Module\LinkChecker\Models\SeoAudit;
use Module\LinkChecker\Models\SeoAuditHistory;
use Module\User\Models\User;
use Module\Utils\UrlNormalizer;

final class StartSeoAuditAction
{
    public function execute(string $link, ?User $user = null): SeoAudit
    {
        $url = UrlNormalizer::make($link)->normalize();
        $seoAudit = SeoAudit::findOrCreateByLink($url);

        if ($this->isDispatchAvailable($seoAudit)) {
            DB::transaction(function () use ($user, $seoAudit): void {
                $this->dispatchJob($seoAudit);
                if ($user !== null) {
                    $this->createHistory($user, $seoAudit);
                }
            });
        }

        return $seoAudit;
    }

    private function dispatchJob(SeoAudit $model): void
    {
        $model->status = SeoAudit::STATUS_WAIT;
        $model->save();
        dispatch(new SeoAuditJob($model->uuid));
//        dispatch(new CrawlInternalLinksJob($model->link));
    }

    private function createHistory(User $user, SeoAudit $seoAudit): void
    {
        $history = new SeoAuditHistory();
        $history->user_id = $user->getKey();
        $history->seo_audit_uuid = $seoAudit->getKey();
        $history->save();
    }

    private function isDispatchAvailable(SeoAudit $seoAudit): bool
    {
//        if ($seoAudit->status === SeoAudit::STATUS_WAIT) {
//            return false;
//        }

        return empty($seoAudit->data_updated_at) || $seoAudit->data_updated_at->diffInMinutes() > 10;
    }
}
