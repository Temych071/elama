<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Implementations;

use App\Infrastructure\DateRange;
use Error;
use Illuminate\Support\Arr;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\Contracts\AnalyticsRouter;
use Module\Source\Analytics\DTO\ItemData;
use Module\Source\Analytics\Services\AnalyticsPath;
use Module\Source\Analytics\Services\MetricsCalculator;

abstract class AbstractRouter implements AnalyticsRouter
{
    /**
     * @inheritDoc
     */
    abstract public function getData(
        Campaign $campaign,
        DateRange $dateRange,
        AnalyticsPath $path,
        $filter = null
    ): ?array;

    protected static function prepareData(?array &$data, AnalyticsPath $path, bool $isEnd = false): array
    {
        if (empty($data)) {
            return [];
        }

        $pathArray = $path->toArray();
        $calculator = app(MetricsCalculator::class);
        foreach ($data as &$item) {
            if (isset($item['metrics'])) {
                $calculator->calcAll($item['metrics']);
            }

            $item = ItemData::fromArray([
                ...$item,
                'path' => $item['path'] ?? [...$pathArray, $item['index']],
                'end' => $item['end'] ?? $isEnd,
            ]);
        }
        return $data;
    }

    protected static function getNestedType(
        array $order,
        mixed $parentType,
        bool &$isLast = false,
        array $excluded = [],
    ): mixed {
        if (!Arr::isList($order)) {
            throw new Error('$order must be a list.');
        }

        if ($excluded !== []) {
            $order = array_values(array_filter($order, static fn ($it): bool => !in_array($it, $excluded, true)));
        }

        if ($order === []) {
            return null;
        }

        if (is_null($parentType)) {
            $key = array_key_first($order);
        } else {
            $key = array_search($parentType, $order, true);
            if ($key === false) {
                return null;
            }

            $key++;
        }

        $isLast = ($key === array_key_last($order));
        return $order[$key] ?? null;
    }
}
