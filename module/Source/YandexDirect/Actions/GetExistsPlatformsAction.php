<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Module\Campaign\Models\Campaign;

final class GetExistsPlatformsAction
{
    public function execute(Campaign $campaign): array
    {
        $source = $campaign->yandexDirectSource;
        $settings_id = $source->settings_id;

        return DB::table('yandex_direct_ad_reports')
            ->select('device')
            ->where('settings_id', $settings_id)
            ->groupBy('device')
            ->get()
            ->map(fn ($item): string => Str::lower($item->device))
            ->toArray();
    }
}
