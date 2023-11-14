<?php

namespace Module\LinkChecker\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Module\LinkChecker\Models\SeoAdsAudit;

class SeoLinkCheckJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $url,
    ) {
        $this->onQueue('seo-link-check');
    }

    public function handle(): void
    {
        $checks = null;
        $model = SeoAdsAudit::startAudit(0, $checks);
    }
}
