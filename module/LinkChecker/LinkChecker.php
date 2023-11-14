<?php

declare(strict_types=1);

namespace Module\LinkChecker;

use Module\LinkChecker\Jobs\AdsAudit\StartAdsAuditJob;
use Module\LinkChecker\Providers\LinkProvider;

final class LinkChecker
{
    public function start(int $sourceId, LinkProvider $linkProvider): void
    {
        StartAdsAuditJob::dispatch($sourceId, $linkProvider);
    }
}
