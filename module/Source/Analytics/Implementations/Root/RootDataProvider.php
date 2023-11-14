<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations\Root;

use App\Infrastructure\DateRange;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\Contracts\AnalyticsDataProviderInterface;
use Module\Source\Analytics\Enums\ChartGroupType;
use Module\Source\Analytics\Enums\RootItemType;
use Module\Source\Analytics\Implementations\AbstractDataProvider;
use Module\Source\Analytics\Implementations\Sources\Avito\AvitoDataProvider;
use Module\Source\Analytics\Implementations\Sources\DirectTrafficYandexMetrika\DirectTrafficYandexMetrikaDataProvider;
use Module\Source\Analytics\Implementations\Sources\SeoYandexMetrika\SeoYandexMetrikaDataProvider;
use Module\Source\Analytics\Implementations\Sources\Vk\VkDataProvider;
use Module\Source\Analytics\Implementations\Sources\VkLeads\VkLeadsDataProvider;
use Module\Source\Analytics\Implementations\Sources\YandexDirect\YandexDirectDataProvider;
use Module\Source\Analytics\Services\MetricsAdder;
use Module\Source\Sources\Models\Source;

final class RootDataProvider extends AbstractDataProvider
{
    public function getChart(Campaign $campaign, DateRange $dateRange, ChartGroupType $groupType): array
    {
        $availableTypes = self::getAvailableItemTypes($campaign);
        $items = [];
        foreach ($availableTypes as $itemType) {
            $items[$itemType->value] = self::getDataProvider($itemType);
        }

        $items = array_map(
            static fn (AnalyticsDataProviderInterface $dataProvider): array => $dataProvider
                ->getChart($campaign, $dateRange, ChartGroupType::DAYS_1),
            $items
        );

        $adder = app(MetricsAdder::class);

        $metricsByDate = [];
        foreach ($availableTypes as $itemType) {
            foreach ($items[$itemType->value] as $item) {
                $date = $item['name'];

                if (!isset($metricsByDate[$date])) {
                    $metricsByDate[$date] = $item['metrics'];
                } else {
                    $adder->sum($metricsByDate[$date], $item['metrics']);
                }
            }
        }

        $res = [];
        foreach ($dateRange->getDaysWithFormat() as $date) {
            $res[] = [
                'name' => $date,
                'index' => "day:$date",
                'metrics' => $metricsByDate[$date] ?? [],
            ];
        }

        return self::groupChartItems($res, $groupType);
    }

    public function getSummary(Campaign $campaign, DateRange $dateRange): array
    {
        return [];
    }

    public function getSources(Campaign $campaign, DateRange $dateRange): array
    {
        $availableTypes = self::getAvailableItemTypes($campaign);
        $items = [];
        foreach ($availableTypes as $itemType) {
            $items[$itemType->value] = self::getDataProvider($itemType);
        }

        $res = [];
        /** @var AnalyticsDataProviderInterface $dataProvider */
        foreach ($items as $type => $dataProvider) {
            $res[] = [
                'index' => $type,
                'name' => match ($type) {
                    RootItemType::AVITO->value => 'Авито',
                    RootItemType::YANDEX_DIRECT->value => 'Яндекс.Директ',
                    RootItemType::VK->value => 'ВКонтакте',
                    RootItemType::VK_LEADS->value => 'ВКонтакте (Лид-формы)',
                    RootItemType::SEO_YANDEX_METRIKA->value => 'Поисковые системы (SEO)',
                    RootItemType::DIRECT_TRAFFIC_YANDEX_METRIKA->value => 'Прямые переходы',
                },
                'metrics' => $dataProvider->getSummary($campaign, $dateRange),
                'end' => match ($type) {
                    RootItemType::DIRECT_TRAFFIC_YANDEX_METRIKA->value => true,
                    default => false,
                },
            ];
        }

        return $res;
    }

    private static function getDataProvider(RootItemType $itemType): AnalyticsDataProviderInterface
    {
        return app(match ($itemType) {
            RootItemType::AVITO => AvitoDataProvider::class,
            RootItemType::YANDEX_DIRECT => YandexDirectDataProvider::class,
            RootItemType::VK => VkDataProvider::class,
            RootItemType::VK_LEADS => VkLeadsDataProvider::class,
            RootItemType::SEO_YANDEX_METRIKA => SeoYandexMetrikaDataProvider::class,
            RootItemType::DIRECT_TRAFFIC_YANDEX_METRIKA => DirectTrafficYandexMetrikaDataProvider::class,
        });
    }

    private static function getAvailableItemTypes(Campaign $campaign): array
    {
        $res = [];
        foreach (RootItemType::cases() as $itemType) {
            $source = match ($itemType) {
                RootItemType::YANDEX_DIRECT => 'yandexDirect',
                RootItemType::SEO_YANDEX_METRIKA, RootItemType::DIRECT_TRAFFIC_YANDEX_METRIKA => 'yandexMetrika',
                RootItemType::VK => 'vk',
                RootItemType::AVITO => 'avito',
                default => null,
            };

            if (
                !is_null($source)
                && self::hasConfiguredSource($campaign, $source)
            ) {
                $res[] = $itemType;
            }
        }

        return $res;
    }

    private static function hasConfiguredSource(Campaign $campaign, string $sourceType): bool
    {
        $key = $sourceType . 'Sources';
        return !is_null($campaign->$key->first(static fn (Source $source): bool => $source->isReady()));
    }
}
