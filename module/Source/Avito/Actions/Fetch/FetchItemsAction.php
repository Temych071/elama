<?php

declare(strict_types=1);

namespace Module\Source\Avito\Actions\Fetch;

use Module\Source\Avito\Enums\ItemStatus;
use Module\Source\Avito\Models\AvitoItem;
use Module\Source\Avito\Services\ItemsAvitoService;
use Module\Source\Sources\Exceptions\UnsupportedSourceTypeException;
use Module\Source\Sources\Models\Source;

final class FetchItemsAction
{
    protected const STATUSES = [
        ItemStatus::ACTIVE,
//        ItemStatus::OLD,
    ];

    protected const PER_PAGE = 100;

    public function execute(Source $source, int $page = 1): ?int
    {
        UnsupportedSourceTypeException::throwIfTypeNotIn($source, Source::TYPE_AVITO);

        $service = new ItemsAvitoService($source->authToken);

        $itemIds = [];
        $res = $service->getItemsInfo(self::PER_PAGE, $page, self::STATUSES);

        if ($res === []) {
            return null;
        }

        $res = array_map(static function ($item) use ($source, &$itemIds): array {
            $itemIds[] = $item['id'];
            return [
                'id' => $item['id'],
                'source_id' => $source->id,
                'price' => $item['price'] ?? null,
                'status' => $item['status'],
                'title' => $item['title'],
                'url' => $item['url'],
                'category_id' => $item['category']['id'],
                'category_name' => $item['category']['name'],
            ];
        }, $res);

        AvitoItem::query()->upsert($res, [
            'id', 'source_id'
        ], [
            'price',
            'status',
            'title',
            'url',
            'category_id',
            'category_name',
        ]);

        if (count($res) < self::PER_PAGE) {
            return null;
        }

        return $page + 1;
    }
}
