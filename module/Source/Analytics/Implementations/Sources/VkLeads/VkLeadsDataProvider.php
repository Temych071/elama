<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations\Sources\VkLeads;

use App\Infrastructure\DateRange;
use Illuminate\Database\Eloquent\Builder;
use JetBrains\PhpStorm\ArrayShape;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\DTO\VkLeadsFilter;
use Module\Source\Analytics\Enums\ChartGroupType;
use Module\Source\Analytics\Enums\VkLeadsItemType;
use Module\Source\Analytics\Enums\VkLeadType;
use Module\Source\Analytics\Implementations\AbstractDataProvider;
use Module\Source\Vk\Models\VkLead;

final class VkLeadsDataProvider extends AbstractDataProvider
{
    /**
     * @return array{requests: int}
     */
    #[ArrayShape(['requests' => "int"])]
    private static function objectToMetrics(?object $obj): array
    {
        return ['requests' => (int)($obj?->requests ?? 0)];
    }

    public function getSummary(Campaign $campaign, DateRange $dateRange): array
    {
        /** @var object $res */
        $res = $this
            ->prepareQuery($campaign, $dateRange)
            ->first();

        if (is_null($res)) {
            return [];
        }

        return self::objectToMetrics($res);
    }

    public function getChart(
        Campaign $campaign,
        DateRange $dateRange,
        ChartGroupType $groupType,
        ?VkLeadsFilter $filter = null,
    ): array {
        $res = $this->prepareQuery($campaign, $dateRange, $filter)
            ->groupByRaw('DATE(created_at)')
            ->selectRaw('DATE(created_at) as day')
            ->get()
            ->keyBy('day')
            ->map(static fn (object $item): array => self::objectToMetrics($item))
            ->toArray();

        $filledData = [];
        foreach ($dateRange->getDaysWithFormat() as $day) {
            $filledData[] = [
                'index' => "day:$day",
                'name' => $day,
                'metrics' => $res[$day] ?? [],
            ];
        }

        return self::groupChartItems($filledData, $groupType);
    }

    public function getLeadTypes(
        Campaign $campaign,
        DateRange $dateRange,
        ?VkLeadsFilter $filter = null,
    ): array {
        return $this->prepareQuery($campaign, $dateRange, $filter)
            ->addSelect('type')
            ->groupBy('type')
            ->get()
            ->map(static fn (object $item): array => [
                'index' => VkLeadsItemType::LEAD_TYPE->value . ':' . $item->type,
                'name' => [
                        VkLeadType::FORM->value => 'Лид-форма',
                        VkLeadType::MESSAGE->value => 'Сообщение',
                    ][$item->type] ?? 'Неизвестное',
                'metrics' => self::objectToMetrics($item),
            ])
            ->toArray();
    }

    private function prepareQuery(
        Campaign $campaign,
        DateRange $dateRange,
        ?VkLeadsFilter $filter = null,
    ): Builder {
        $q = VkLead::query()
            ->selectRaw('COUNT(*) as requests')
            ->whereIn('source_id', $campaign->vkSources->pluck('id'))
            ->whereInDateRange($dateRange, 'created_at');

        if (!is_null($filter?->lead_type)) {
            $q->where('type', $filter?->lead_type);
        }

        return $q;
    }
}
