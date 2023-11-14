<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\PlanFact;

use App\Infrastructure\DateRange;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Module\PlanFact\Contracts\CabinetSourceService;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Data\CampaignData;
use Module\Source\YandexDirect\Models\DirectCampaign;

final class YandexDirectCabinetSourceService implements CabinetSourceService
{
    public function __construct(
        private readonly Source $source,
    ) {
    }

    public function getCampaigns(): array
    {
        return DirectCampaign::query()
            ->select(['name'])
            ->where('settings_id', $this->source->settings_id)
            ->get()
            ->map(static fn ($campaign) => $campaign->name)
            ->toArray();
    }

    /**
     * @return array[]
     */
    #[ArrayShape([['date' => Carbon::class, 'clicks' => "int", 'expenses' => "int"]])]
    public function getStatistics(DateRange $period, ?array $filters = null): array
    {
        return $this->newQuery($period, $filters)
            ->selectRaw("date, SUM(clicks) AS clicks, SUM(cost) AS cost")
            ->groupBy('date')
            ->get()
            ->map(static fn ($item): array => [
                'date' => $item->date,
                'clicks' => $item->clicks,
                'expenses' => $item->cost / 1_000_000,
            ])
            ->toArray();
    }

    private function newQuery(DateRange $period, ?array $filters = null): Builder
    {
        $q = DB::table('yandex_direct_ad_reports')
            ->where('settings_id', $this->source->settings_id)
            ->whereDate('date', '>=', $period->getFrom())
            ->whereDate('date', '<=', $period->getTo());

        return $this->applyFilters($q, $filters ?? []);
    }

    private function applyFilters(Builder $q, array $filters): Builder
    {
        if (!empty($filters['device'])) {
            $q->where('device', Str::upper($filters['device']));
        }

        if (!empty($filters['campaign_name'])) {
            $ids = DirectCampaign::query()
                ->select(['id'])
                ->whereIn('name', (array)$filters['campaign_name'])
                ->where('settings_id', $this->source->settings_id)
                ->get()
                ->map(static fn ($item) => $item->id)
                ->toArray();
        } else {
            $selectedCampaigns = $this->source->settings?->campaigns?->toCollection();

            if ($selectedCampaigns !== null) {
                $ids = $selectedCampaigns
                    ->map(static fn (CampaignData $item): int => $item->Id)
                    ->toArray();
            }
        }

        if (!empty($ids)) {
            $q->whereIn('campaign_id', $ids);
        }

        return $q;
    }
}
