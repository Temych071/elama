<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika\Actions\PlanFact;

use Illuminate\Support\Facades\DB;
use Module\Source\Sources\Models\Source;

final class GetExistsDomainsAction
{
    public function execute(Source $source, string $visit_source): array
    {
        return DB::table('metrika_conversions')
//            ->select(['start_url'])
            ->selectRaw("substring_index(substring_index(start_url, '://', -1), '/', 1) as start_url")
            ->distinct()
            ->where('source', $visit_source)
            ->where('settings_id', $source->settings_id)
            ->get()
            ->map(static fn ($item) => $item->start_url)
//            ->map(static fn($item) => Str::before(Str::after($item->start_url, '://'), '/'))
//            ->unique()
            ->toArray();
    }
}
