<?php

declare(strict_types=1);

namespace Module\Source\Vk\PlanFact;

use App\Infrastructure\DateRange;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
use Module\PlanFact\Contracts\CabinetSourceService;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Data\CampaignData;
use Module\Source\Vk\Models\VkCampaignParam;
use Module\Source\Vk\Models\VkSettings;

final class VkCabinetSourceService implements CabinetSourceService
{
    public function __construct(
        private readonly Source $source,
    ) {
    }

    public function getCampaigns(): array
    {
        return VkCampaignParam::query()
            ->where('setting_id', $this->source->settings_id)
            ->pluck('name')
            ->toArray();
    }

    /**
     * @return array[]
     */
    #[ArrayShape([['date' => Carbon::class, 'clicks' => "int", 'expenses' => "int"]])]
    public function getStatistics(DateRange $period, ?array $filters = null): array
    {
        $query = DB::table('vk_ads_statistics')
            ->where('settings_id', $this->source->settings_id)
            ->whereDate('day', '>=', $period->getFrom())
            ->whereDate('day', '<=', $period->getTo());

        if (!is_null($filters)) {
            $query = $this->applyFilters($query, $filters);
        }

        return $query
            ->selectRaw("day, SUM(clicks) AS clicks, SUM(spent) AS spent")
            ->groupBy('day')
            ->get()
            ->map(static fn ($item): array => [
                'date' => $item->day,
                'clicks' => $item->clicks,
                'expenses' => $item->spent / 100,
            ])
            ->toArray();
    }

    private function applyFilters(Builder $q, array $filters): Builder
    {
        // todo add device filter
//        if (!empty($filters['device'])) {
//            $q->where('device', Str::upper($filters['device']));
//        }

        if (!empty($filters['campaign_name'])) {
            $ids = VkCampaignParam::query()
                ->where('setting_id', $this->source->settings_id)
                ->whereIn('name', (array)$filters['campaign_name'])
                ->pluck('id')
                ->toArray();
        } else {
            /** @var VkSettings $settings */
            $settings = $this->source->settings;

            $ids = $settings
                ->campaigns
                ?->toCollection()
                ?->map(static fn (CampaignData $it): int => $it->id)
                ?->toArray();
        }

        if (!empty($ids)) {
            $q->whereIn('campaign_id', $ids);
        }

        return $q;
    }
}
