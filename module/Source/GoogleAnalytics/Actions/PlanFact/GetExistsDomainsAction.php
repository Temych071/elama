<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Actions\PlanFact;

use Illuminate\Support\Facades\DB;
use Module\Source\Sources\Models\Source;

final class GetExistsDomainsAction
{
    public function execute(Source $source, string $visit_source): array
    {
        return DB::table('analytics_conversions')
            ->select(['hostname'])
            ->distinct()
            ->where('source', $visit_source)
            ->where('settings_id', $source->settings_id)
            ->get()
            ->map(static fn ($item) => $item->hostname)
            ->toArray();
    }
}
