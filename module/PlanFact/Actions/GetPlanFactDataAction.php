<?php

namespace Module\PlanFact\Actions;

use App\Infrastructure\DateRange;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Module\Campaign\Models\Campaign;
use Module\PlanFact\Contracts\EcommerceSourceService;
use Module\PlanFact\Contracts\MetricsSourceService;
use Module\PlanFact\DTO\PlanFactData;
use Module\PlanFact\DTO\PlanFactItem;
use Module\PlanFact\DTO\PlanValues;
use Module\PlanFact\Models\PlanSettings;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Exceptions\InvalidDataCollectionModification;

class GetPlanFactDataAction
{
    private const FIELDS_PROPS = [
        'cpc' => [
            'reverse' => true,
        ],
        'cpl' => [
            'reverse' => true,
        ],
        'drr' => [
            'reverse' => true,
            'units' => '%'
        ],
        'cr' => [
            'units' => '%'
        ],
    ];

    private const ONLY_ECOMMERCE_FIELDS = [
        'income',
        'drr',
    ];

    /**
     * @param  MetricsSourceService  $metricsService
     * @param  ?EcommerceSourceService  $ecommerceService
     * @return DataCollection|PlanFactData[]
     * @throws InvalidDataCollectionModification
     */
    public function execute(
        Campaign $campaign,
        array $statsServices,
        array $ecommerceServices,
        array $cabinetServices,
        DateRange $period,
        array $filters,
        PlanSettings $planSettings,
    ): DataCollection {
        $mergeSumCallback = static function (Collection $a, Collection $b): \Illuminate\Support\Collection {
            $res = collect();
            $keys1 = collect([...$a->keys(), ...$b->keys()])->unique()->values();

            foreach ($keys1 as $key1) {
                $res[$key1] = collect();
                $a2 = collect($a[$key1] ?? null);
                $b2 = collect($b[$key1] ?? null);
                $keys2 = collect([...$a2->keys(), ...$b2->keys()])->unique()->values();

                foreach ($keys2 as $key2) {
                    if ($key2 === 'date') {
                        $res[$key1][$key2] = $a[$key1][$key2] ?? $b[$key1][$key2] ?? null;
                    } else {
                        $res[$key1][$key2] = ($a[$key1][$key2] ?? 0) + ($b[$key1][$key2] ?? 0);
                    }
                }
            }
            return $res;
        };

        $both = $this->getCabinetsData($cabinetServices, $period, $filters);
        $both = collect($both)->keyBy('date');

        $metrics = collect($this->getStatsData($statsServices, $period, $filters));
        $both = $mergeSumCallback($both, $metrics);

        $hasEcommerce = false;
        foreach ($ecommerceServices as $ecommerceService) {
            if ($ecommerceService->isEcommerceEnabled()) {
                $ecommerce = $ecommerceService->getEcommerce($period, $filters);
                $ecommerce = collect($ecommerce)->keyBy('date');

                $both = $mergeSumCallback($both, $ecommerce);

                $hasEcommerce = true;
            }
        }
        $both = $both->toArray();

        $plans = $planSettings->values->toCollection();

        $mapForSorting = [];
        foreach ($campaign->planfact_order as $item) {
            if (
                $hasEcommerce
                || !in_array($item['field'], self::ONLY_ECOMMERCE_FIELDS, true)
            ) {
                $mapForSorting[$item['field']] = $item['num'];
            }
        }

        $lines = [];
        $summaries = [];
        $days = $period->getDays();

        foreach ($days as $date) {
            $date = Carbon::make($date);

            /** @var PlanValues $plan */
            $plan = $plans->first(static fn ($item): ?bool => $date?->isSameMonth($item->month));
            if (is_null($plan)) {
                continue;
            }

            $rate = 1 / $plan->month->daysInMonth;

            $values = array_merge(
                [
                    'expenses' => 0,
                    'income' => 0,
                    'clicks' => 0,
                    'requests' => 0,
//                    'cr' => 0,
//                    'cpl' => 0,
//                    'cpc' => 0,
//                    'drr' => 0,
                ],
                $both[$date?->format('Y-m-d')] ?? [],
            );
            $values['cpc'] = empty($values['clicks']) ? (0) : ($values['expenses'] ?? 0) / $values['clicks'];
            $values['cpl'] = empty($values['requests']) ? (0) : ($values['expenses'] ?? 0) / $values['requests'];
            $values['drr'] = empty($values['income']) ? (0) : (($values['expenses'] ?? 0) / $values['income']) * 100;
            $values['cr'] = empty($values['clicks']) ? (0) : (($values['requests'] ?? 0) / $values['clicks']) * 100;

            $values = array_intersect_key($values, $plan->toArray());

            foreach ($values as $key => $value) {
                if ($key === 'date') {
                    continue;
                }

                if (self::isSum($key)) {
                    $summaries[$key]['fact'] = self::uniteValues($key, $summaries[$key]['fact'] ?? 0, $value);
                }

                $lines[$key][] = [
                    'plan' => $plan->$key * (self::isAvg($key) ? 1 : $rate),
                    'fact' => (float)$value,
                    'date' => $date,
                ];
            }

            if (isset($mapForSorting['cpc'])) {
                $summaries['cpc']['fact'] = empty($summaries['clicks']['fact'])
                    ? (0)
                    : ($summaries['expenses']['fact'] ?? 0) / $summaries['clicks']['fact'];
            }

            if (isset($mapForSorting['cpl'])) {
                $summaries['cpl']['fact'] = empty($summaries['requests']['fact'])
                    ? (0)
                    : ($summaries['expenses']['fact'] ?? 0) / $summaries['requests']['fact'];
            }

            if (isset($mapForSorting['drr'])) {
                $summaries['drr']['fact'] = empty($summaries['income']['fact'])
                    ? (0)
                    : (($summaries['expenses']['fact'] ?? 0) / $summaries['income']['fact']) * 100;
            }

            if (isset($mapForSorting['cr'])) {
                $summaries['cr']['fact'] = empty($summaries['clicks']['fact'])
                    ? (0)
                    : (($summaries['requests']['fact'] ?? 0) / $summaries['clicks']['fact']) * 100;
            }
        }

        foreach (array_keys($summaries) as $key) {
            foreach ($plans as $plan) {
                $summaries[$key]['plan'] = self::uniteValues(
                    $key,
                    $summaries[$key]['plan'] ?? 0,
                    self::getActualPlan($plan, $key, $period) ?? 0,
                );
            }
        }

        $lines = self::groupLines(
            $lines,
            match (true) {
                $period->getLength() <= 7 => 1,
                $period->getLength() <= 21 => 2,
                default => 7,
            },
        );

        $lines = array_intersect_key($lines, $mapForSorting);
        $summaries = array_intersect_key($summaries, $mapForSorting);

        $res = [];
        foreach ($summaries as $field => $data) {
            if (!isset($lines[$field])) {
                continue;
            }
            $res[] = new PlanFactData(
                title: $field,
                summary: PlanFactItem::from($data),
                linear: PlanFactItem::collect($lines[$field]),
                units: self::FIELDS_PROPS[$field]['units'] ?? '',
                reverse: self::FIELDS_PROPS[$field]['reverse'] ?? '',
            );
        }

        usort(
            $res,
            static fn($it1, $it2): int => $mapForSorting[$it1->title] <=> $mapForSorting[$it2->title]
        );

        return new DataCollection(PlanFactData::class, $res);
    }

    private static function groupLines(array $lines, int $count): array
    {
        if ($count < 2) {
            return $lines;
        }

        $groupedLines = [];
        foreach ($lines as $key => $line) {
            $chunks = array_chunk($line, $count);
            foreach ($chunks as $chunk) {
                $chunkData = [
                    'date' => DateRange::make([$chunk[0]['date'], $chunk[array_key_last($chunk)]['date']])
                ];
                foreach ($chunk as $item) {
                    $chunkData['plan'] = self::uniteValues(
                        $key,
                        $chunkData['plan'] ?? 0,
                        $item['plan'],
                    );
                    $chunkData['fact'] = self::uniteValues(
                        $key,
                        $chunkData['fact'] ?? 0,
                        $item['fact'],
                    );
                }
                $groupedLines[$key][] = $chunkData;
            }
        }

        $pointsCount = $groupedLines === [] ? 0 : count($groupedLines[array_key_first($groupedLines)]);

        for ($i = 0; $i < $pointsCount; $i++) {
            $get = static fn ($key) => $groupedLines[$key][$i]['fact'] ?? null;

            $groupedLines['cpl'][$i]['fact'] = empty($get('requests'))
                ? (0)
                : $get('expenses') / $get('requests');

            $groupedLines['cpc'][$i]['fact'] = empty($get('clicks'))
                ? (0)
                : $get('expenses') / $get('clicks');

            $groupedLines['cr'][$i]['fact'] = empty($get('clicks'))
                ? (0)
                : $get('requests') / $get('clicks') * 100;

            $groupedLines['drr'][$i]['fact'] = empty($get('income'))
                ? (0)
                : $get('expenses') / $get('income') * 100;
        }

        return $groupedLines;
    }

    private static function getActualPlan(
        PlanValues $plan,
        string $key,
        DateRange $period
    ): int|float|null {
        $from = $period->getFrom();
        $to = $period->getTo();
        $start = $plan->month->clone()->startOfMonth();
        $end = $plan->month->clone()->endOfMonth();

        if ($end->isBefore($from) || $start->isAfter($to)) {
            return 0;
        }

        if (self::isAvg($key)) {
            return $plan->$key;
        }
        $rate = 1;


        if ($plan->month->isSameMonth($from)) {
            $rate -= $start->diffInDays($from) / $from->daysInMonth;
        }
        if ($plan->month->isSameMonth($to)) {
            $rate -= $end->diffInDays($to) / $to->daysInMonth;
        }

        return $plan->$key * $rate;
    }

    /**
     * @param  MetricsSourceService[]  $statsServices
     */
    private function getStatsData(array $statsServices, DateRange $period, array $filters): array
    {
        $res = [];
        foreach ($statsServices as $service) {
            $res[] = $service->getConversions($period, $filters);
        }
//        $res = array_merge(...$res);

        $byDates = [];
        $statsFields = ['clicks', 'requests'];
        foreach ($res as $group) {
            foreach ($group as $item) {
                if (!isset($byDates[$item['date']])) {
                    $byDates[$item['date']] = $item;

                    foreach ($statsFields as $field) {
                        $byDates[$item['date']][$field] = (int)($byDates[$item['date']][$field] ?? 0);
                    }
                } else {
                    foreach ($statsFields as $field) {
                        $byDates[$item['date']][$field] += ($item[$field] ?? 0);
                    }
                }
            }
        }

        return $byDates;
    }

    #[ArrayShape([['date' => Carbon::class, 'clicks' => "int", 'expenses' => "int"]])]
    private function getCabinetsData(array $cabinetServices, DateRange $period, array $filters): array
    {
        $data = collect();
        foreach ($cabinetServices as $service) {
            $res = collect($service->getStatistics($period, $filters))
                ->groupBy('date');

            if ($data->isEmpty()) {
                $data = $res;
            } else {
                foreach ($res as $date => $values) {
                    if (!$data->has($date)) {
                        $data->offsetSet($date, collect());
                    }

                    foreach ($values as $value) {
                        $data->offsetGet($date)->push($value);
                    }
                }
            }
        }

        if ($data->count() === 0) {
            return [];
        }

        $data = $data->map(static function ($items, $date): array {
            $sumItem = [
                'date' => $date,
                'expenses' => 0,
                'clicks' => 0,
            ];

            foreach ($items as $item) {
                $sumItem['expenses'] = ($sumItem['expenses'] ?? 0) + $item['expenses'];
                $sumItem['clicks'] = ($sumItem['clicks'] ?? 0) + $item['clicks'];
            }

            return $sumItem;
        });

        return $data->toArray();
    }

    #[Pure]
    private static function uniteValues(string $key, int|float $val1, int|float $val2): int|float
    {
        if (self::isSum($key)) {
            return $val1 + $val2;
        }

        if (self::isAvg($key)) {
            $res = $val1 + $val2;
            if ($val1 === 0 || $val2 === 0) {
                return $res;
            }
            return $res / 2;
        }

        return $val1;
    }

    private static function isSum(string $key): bool
    {
        return in_array($key, PlanSettings::METRIC_SUM_VALUES, true);
    }

    private static function isAvg(string $key): bool
    {
        return in_array($key, PlanSettings::METRIC_AVG_VALUES, true);
    }
}
