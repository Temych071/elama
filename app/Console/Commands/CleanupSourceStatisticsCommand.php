<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Module\Source\Sources\Models\Source;

class CleanupSourceStatisticsCommand extends Command
{
    private const CHUNK_COUNT = 5;

    protected $signature = 'source:cleanup-statistics';

    protected $description = 'Remove old statistics data on deleted sources';

    public function handle(): void
    {
        Source::query()
            ->onlyTrashed()
            ->whereDate('deleted_at', '<', Carbon::now()->subWeeks(2))
            ->get()
            ->groupBy('settings_type')
            ->map(fn ($sources) => $sources->pluck('settings_id'))
            ->each(function ($ids, $type): \Illuminate\Support\Collection {
                $total = is_countable($ids) ? count($ids) : 0;

                return collect($ids)
                    ->chunk(self::CHUNK_COUNT)
                    ->each(function ($chunk, $key) use ($total, $type): void {
                        $deleted = 0;

                        if ($type === Source::TYPE_VK) {
                            $deleted = $this->cleanVk($chunk);
                        } elseif ($type === Source::TYPE_YANDEX_DIRECT) {
                            $deleted = $this->cleanDirect($chunk);
                        } elseif ($type === Source::TYPE_YANDEX_METRIKA) {
                            $deleted = $this->cleanMetrika($chunk);
                        }

                        $progress = min(($key + 1) * self::CHUNK_COUNT, $total);
                        $this->line("[$type $progress/$total] $deleted rows deleted");
                    });
            });
    }

    private function cleanVk($ids): int
    {
        $count = 0;

        $count += DB::table('vk_ads_statistics')
            ->whereIn('settings_id', $ids)
            ->delete();

        $count += DB::table('vk_ad_params')
            ->whereIn('setting_id', $ids)
            ->delete();

        return $count + DB::table('vk_campaign_params')
            ->whereIn('setting_id', $ids)
            ->delete();
    }

    private function cleanDirect($ids): int
    {
        $count = 0;

        $count += DB::table('yandex_direct_ad_reports')
            ->whereIn('settings_id', $ids)
            ->delete();

        $count += DB::table('yandex_direct_ads')
            ->whereIn('settings_id', $ids)
            ->delete();

        $count += DB::table('yandex_direct_ad_groups')
            ->whereIn('settings_id', $ids)
            ->delete();

        return $count + DB::table('yandex_direct_campaigns')
            ->whereIn('settings_id', $ids)
            ->delete();
    }

    private function cleanMetrika($ids): int
    {
        $count = 0;

        $count += DB::table('metrika_visits')
            ->whereIn('settings_id', $ids)
            ->delete();

        $count += DB::table('metrika_conversions')
            ->whereIn('settings_id', $ids)
            ->delete();

        return $count + DB::table('yandex_metrika_ecommerce')
            ->whereIn('settings_id', $ids)
            ->delete();
    }
}
