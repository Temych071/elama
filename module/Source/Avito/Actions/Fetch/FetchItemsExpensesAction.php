<?php

declare(strict_types=1);

namespace Module\Source\Avito\Actions\Fetch;

use App\Infrastructure\DateRange;
use Illuminate\Support\Carbon;
use Module\Source\Avito\Exceptions\AvitoInvalidDateRangeException;
use Module\Source\Avito\Models\AvitoItemStats;
use Module\Source\Avito\Services\BalanceAvitoService;
use Module\Source\Sources\Models\Source;

final class FetchItemsExpensesAction
{
//    private const SERVICE_DAYS = [
//        'До 2 раз больше просмотров на 7 дней' => 7, // id -
//        'До 5 раз больше просмотров на 7 дней' => 7, // id -
//        'До 10 раз больше просмотров на 7 день' => 7, // id -
//
//        'До 2 раз больше просмотров на 1 дней' => 1, // id -
//        'До 5 раз больше просмотров на 1 день' => 1, // id -
//        'До 10 раз больше просмотров на 1 день' => 1, // id -
//
//        'Сделать XL объявлением' => 7, // id - 16
//        'Разовое размещение' => null, // id - 6
//        'Выделить объявление' => null, // id - 2
//    ];

    private const ALLOWED_OP_TYPES = [
        'резервирование средств под услугу',
    ];

    /**
     * @throws AvitoInvalidDateRangeException
     */
    public function execute(Source $source, DateRange $dateRange): void
    {
        $service = new BalanceAvitoService($source->authToken);

        foreach ($dateRange->chunk(7) as $chunk) {
            $res = array_values(
                array_reduce($service->getOperationsHistory($chunk), static function ($res, $op) use ($source) {
                    if (!in_array($op['operationType'], self::ALLOWED_OP_TYPES, true)) {
                        return $res;
                    }

                    $key = $op['itemId'] ?? 0;
                    if (!isset($res[$key])) {
                        $res[$key] = [
                            'source_id' => $source->id,
                            'item_id' => $op['itemId'] ?? 0,
                            'date' => Carbon::parse($op['updatedAt'])->format('Y-m-d'),

                            'expenses' => $op['amountRub'],
                        ];
                    } else {
                        $res[$key]['expenses'] += $op['amountRub'];
                    }

                    return $res;
                }, [])
            );

            AvitoItemStats::query()
                ->upsert($res, [
                    'source_id', 'item_id', 'date',
                ], [
                    'expenses',
                ]);
        }
    }
}
