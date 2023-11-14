<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations\Sources\Vk;

use App\Exceptions\BusinessException;
use App\Infrastructure\DateRange;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\DTO\CabinetsFilter;
use Module\Source\Analytics\Enums\CabinetItemType;
use Module\Source\Analytics\Enums\RootItemType;
use Module\Source\Analytics\Implementations\AbstractRouter;
use Module\Source\Analytics\Implementations\Sources\VkLeads\VkLeadsDataProvider;
use Module\Source\Analytics\Implementations\Sources\VkLeads\VkLeadsRouter;
use Module\Source\Analytics\PathItems\VkPathItem;
use Module\Source\Analytics\Services\AnalyticsPath;
use Module\Source\Sources\Models\Source;

final class VkRouter extends AbstractRouter
{
    public function __construct(
        private readonly VkDataProvider $dataProvider,
        private readonly VkLeadsDataProvider $leadsDataProvider,
    ) {
    }

    /**
     * @inheritDoc
     * @throws BusinessException
     */
    public function getData(
        Campaign $campaign,
        DateRange $dateRange,
        AnalyticsPath $path,
        $filter = new CabinetsFilter(),
    ): ?array {
        $filter->source_type = Source::TYPE_VK;

        $item = null;
        while (!$path->isEnd()) {
            $item = VkPathItem::make($path->step());

            if ($item->isChartItem()) {
                $res = $this->dataProvider->getChart($campaign, $dateRange, $item->getChartGroupType(), $filter);
                return self::prepareData($res, $path, true);
            }

            if ($item->isVkLeads()) {
                return app(VkLeadsRouter::class)->getData($campaign, $dateRange, $path);
            }

            switch ($item->getCabinetItemType()) {
                case CabinetItemType::ACCOUNT:
                    $filter->account_ids = [$item->getItemIndex()];
                    break;

                case CabinetItemType::CAMPAIGN:
                    $filter->campaign_ids = [$item->getItemIndex()];
                    break;

                case CabinetItemType::AD:
                    $filter->ad_ids = [$item->getItemIndex()];
                    break;

                default:
                    break;
            }
        }

        $isLast = false;
        $next = self::getNestedType(
            $campaign->getAnalyticsCabinetOrder(),
            $item?->getCabinetItemType(),
            $isLast,
            [CabinetItemType::AD_GROUP],
        );

        $data = match ($next) {
            CabinetItemType::ACCOUNT => $this->dataProvider->getAccounts($campaign, $dateRange, $filter),
            CabinetItemType::CAMPAIGN => $this->dataProvider->getCampaigns($campaign, $dateRange, $filter),
            CabinetItemType::AD => $this->dataProvider->getAds($campaign, $dateRange, $filter),
            default => null,
        };

        if (is_null($item) && $campaign->hasVkLeads()) {
            array_unshift($data, [
                'index' => RootItemType::VK_LEADS->value,
                'name' => 'ВКонтакте (Лид-формы)',
                'metrics' => $this->leadsDataProvider->getSummary($campaign, $dateRange),
                'end' => false,
            ]);
        }

        return self::prepareData($data, $path, $isLast);
    }
}
