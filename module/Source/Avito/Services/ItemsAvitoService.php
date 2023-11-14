<?php

declare(strict_types=1);

namespace Module\Source\Avito\Services;

use App\Infrastructure\DateRange;
use Illuminate\Support\Arr;
use Module\Source\Avito\Enums\ItemStatsField;
use Module\Source\Avito\Enums\ItemStatsGrouping;
use Module\Source\Avito\Enums\ItemStatus;

final class ItemsAvitoService extends BaseAvitoService
{
    /**
     * @param ItemStatus|ItemStatus[] $status
     * @param int|null $categoryId
     */
    public function getItemsInfo(
        int $perPage = 25,
        int $page = 1,
        ItemStatus|array $status = ItemStatus::ACTIVE,
        int $categoryId = null,
    ): array {
        return $this->get('/core/v1/items', [
                'per_page' => $perPage,
                'page' => $page,
                'status' => implode(',', self::enumsToVals(Arr::wrap($status))),
                'category' => $categoryId,
            ])['resources'] ?? [];
    }

    /**
     * @param int[] $itemIds
     * @param ItemStatsField|ItemStatsField[] $fields
     */
    public function getItemsStats(
        DateRange $dateFromTo,
        array $itemIds,
        ItemStatsField|array $fields = [ItemStatsField::CONTACTS, ItemStatsField::FAVORITES, ItemStatsField::VIEWS],
        ItemStatsGrouping $grouping = ItemStatsGrouping::DAY,
    ): array {
        return $this->post("/stats/v1/accounts/{$this->getUserId()}/items", [
                'dateFrom' => $dateFromTo->getFrom()->format('Y-m-d'),
                'dateTo' => $dateFromTo->getTo()->format('Y-m-d'),
                'itemIds' => array_values($itemIds),
                'fields' => self::enumsToVals(Arr::wrap($fields)),
                'periodGrouping' => $grouping->value,
            ])['result']['items'] ?? [];
    }

    private static function enumsToVals(array $enums): array
    {
        return array_map(static fn ($item) => $item->value, $enums);
    }
}
