<?php

/** @noinspection PhpIllegalArrayKeyTypeInspection */

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations\Stats;

use App\Infrastructure\DateRange;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\Contracts\AnalyticsStatsProviderInterface;
use Module\Source\Analytics\DTO\CabinetsFilter;
use Module\Source\Analytics\Implementations\Stats\YandexMetrika\YandexMetrikaStatsProvider;
use Module\Source\Analytics\Services\MetricsAdder;
use Module\Source\Sources\Models\Source;

final class StatsProvider implements AnalyticsStatsProviderInterface
{
    public function __construct(
        private readonly MetricsAdder $metricsAdder,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getStats(
        Campaign $campaign,
        DateRange $dateRange,
        CabinetsFilter $filter,
        ?string $groupBy = null,
    ): array {
        $statsProviders = [];

        if (!empty($campaign->yandexMetrikaSources)) {
            $statsProviders[] = app(YandexMetrikaStatsProvider::class);
        }

        if (empty($statsProviders)) {
            return [];
        }

        $statsProvidersData = array_map(
            static fn (AnalyticsStatsProviderInterface $provider): array =>
                $provider->getStats($campaign, $dateRange, $filter, $groupBy),
            $statsProviders
        );

        if (is_null($groupBy)) {
            return $this->metricsAdder->sumArray($statsProvidersData[0] ?? []);
        }

        $keys = [];
        foreach ($statsProvidersData as $providersData) {
            array_push($keys, ...array_keys($providersData));
        }
        $keys = array_unique($keys);

        $res = [];
        foreach ($keys as $key) {
            $item = [];
            foreach ($statsProvidersData as $data) {
                if (!empty($data[$key])) {
                    $this->metricsAdder->sum($item, $data[$key]);
                }
            }
            $res[$key] = $item;
        }

        return $res;
    }

    public function joinStats(
        array $data,
        Campaign $campaign,
        DateRange $dateRange,
        CabinetsFilter $filter,
        ?string $groupBy = null,
    ): array {
        $stats = $this->getStats($campaign, $dateRange, $filter, $groupBy);

        if (is_null($groupBy)) {
            return $this->metricsAdder->sum($data, $stats);
        }

        $statsKeys = array_keys($stats);
        $statsKeysProxy = null;
        if (
            $groupBy === 'dg_1'
            && in_array($filter->source_type, Source::CABINET_SOURCES, true)
        ) {
            $statsKeysProxy = Source::query()
                ->select(['settings_id', 'id'])
                ->whereIn('id', $statsKeys)
                ->get()
                ->mapWithKeys(static fn ($item): array => [$item->settings_id => $item->id])
                ->toArray();
        }

        $keys = array_unique([
            ...array_keys($data),
            ...(empty($statsKeysProxy) ? $statsKeys : array_keys($statsKeysProxy)),
        ]);

        $res = [];
        foreach ($keys as $key) {
            $item = [];
            if (!empty($data[$key])) {
                $item = $data[$key];
            }

            $statsItem = $stats[$statsKeysProxy[$key] ?? $key] ?? null;

            if (!empty($statsItem)) {
                $item = $this->metricsAdder->sum($item, $statsItem);
            }

            $res[$key] = $item;
        }

        return $res;
    }
}
