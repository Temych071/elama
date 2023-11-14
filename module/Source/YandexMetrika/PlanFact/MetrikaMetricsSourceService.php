<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika\PlanFact;

use App\Infrastructure\DateRange;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Module\PlanFact\Contracts\EcommerceSourceService;
use Module\PlanFact\Contracts\MetricsSourceService;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexMetrika\Data\GoalData;

final class MetrikaMetricsSourceService implements MetricsSourceService, EcommerceSourceService
{
    public function __construct(
        private readonly Source $source,
    ) {
    }

    #[ArrayShape([['date' => Carbon::class, 'requests' => "int"]])]
    public function getConversions(DateRange $period, ?array $filters = null): array
    {
        $res = $this->newQuery($period, $filters)
            ->selectRaw('date, SUM(reaches) AS reaches')
            ->groupBy('date')
            ->get()
            ->map(static fn ($item): array => [
                'date' => $item->date,
                'requests' => $item->reaches,
            ])
            ->toArray();

        if (!empty($filters['seo']) && $filters['seo']['enabled']) {
            $res = array_merge_recursive(
                $res,
                $this->newQuery($period, ['seo' => $filters['seo']], 'metrika_visits')
                    ->selectRaw('date, SUM(visits) as clicks')
                    ->groupBy('date')
                    ->get()
                    ->map(static fn ($item): array => [
                        'date' => $item->date,
                        'clicks' => $item->clicks,
                    ])
                    ->toArray(),
            );
        }

        return $res;
    }

    /**
     * @inheritDoc
     */
    #[ArrayShape([['date' => Carbon::class, 'income' => "int"]])]
    public function getEcommerce(
        DateRange $period,
        ?array $filters = null
    ): array {
        return $this->newQuery($period, $filters, 'yandex_metrika_ecommerce')
            ->selectRaw('date, SUM(ecommerce_revenue) AS income')
            ->groupBy('date')
            ->get()
            ->map(static fn ($item): array => [
                'date' => $item->date,
                'income' => $item->income,
            ])
            ->toArray();
    }

    /**
     * @return string[]
     */
    public function getDomains(?DateRange $period = null, ?array $filters = null): array
    {
        return $this->newQuery($period, $filters)
            ->selectRaw("substring_index(substring_index(start_url, '://', -1), '/', 1) as start_url")
            ->distinct()
            ->get()
            ->map(static fn ($item) => $item->start_url)
            ->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getDevices(?DateRange $period = null, ?array $filters = null): array
    {
        return $this->newQuery($period, $filters)
            ->select(['device_category'])
            ->distinct()
            ->get()
            ->map(static fn ($item) => $item->device_category)
            ->toArray();
    }

    private function newQuery(?DateRange $period, ?array $filters, string $table = 'metrika_conversions'): Builder
    {
        $q = DB::table($table)
            ->where('settings_id', $this->source->settings_id);

        if (!is_null($period)) {
            $q->whereDate('date', '>=', $period->getFrom())
                ->whereDate('date', '<=', $period->getTo());
        }

        if (!is_null($filters)) {
            $q = $this->applyFilters($q, $filters, !Str::contains($table, 'conversion'));
        }

        return $q;
    }

    private const SIMPLE_FILTERS = [
        /* filter_key => col */
        'device' => 'device_category',
    ];

    private function applyFilters(Builder $q, array $filters, bool $ignoreGoals = false): Builder
    {
        foreach (self::SIMPLE_FILTERS as $key => $col) {
            if (!empty($filters[$key])) {
                $q->where($col, $filters[$key]);
            }
        }

        if (!empty($filters['utm_campaign'])) {
            if (is_array($filters['utm_campaign'])) {
                $q->whereIn('campaign', $filters['utm_campaign']);
            } else {
                $q->where('campaign', $filters['utm_campaign']);
            }
        }

        if (!empty($filters['utm_medium'])) {
            if (is_array($filters['utm_medium'])) {
                $q->whereIn('medium', $filters['utm_medium']);
            } else {
                $q->where('medium', $filters['utm_medium']);
            }
        }

        if (!empty($filters['utm_source'])) {
            if (is_array($filters['utm_source'])) {
                $q->whereIn('source', $filters['utm_source']);
            } else {
                $q->where('source', $filters['utm_source']);
            }
        }

        if (!$ignoreGoals) {
            if (!empty($filters['goals'])) {
                $q->whereIn('goal_id', $filters['goals']);
            } else {
                $q->whereIn(
                    'goal_id',
                    $this->source
                        ->settings
                        ->goals
                        ->toCollection()
                        ->map(static fn (GoalData $goal): int => $goal->id)
                        ->toArray(),
                );
            }
        }

        if (!empty($filters['domain'])) {
            $q->where('start_url', 'LIKE', "%{$filters['domain']}%");
        }

        $q->where(static function (Builder $q) use ($filters): void {
            if (!empty($filters['cabinets'])) {
                $cabinets = $filters['cabinets'];
                $q->orWhere(static function (Builder $q) use ($cabinets): \Illuminate\Database\Query\Builder {
                    foreach ($cabinets as $cabinet) {
                        $q->orWhere(static function (Builder $q) use ($cabinet): \Illuminate\Database\Query\Builder {
                            if (is_array(self::CABINET_SOURCE_UTMS[$cabinet])) {
                                $values = [];
                                foreach (self::CABINET_SOURCE_UTMS[$cabinet] as $item) {
                                    if (is_array($item)) {
                                        $q->orWhere('source', $item['operator'], $item['value']);
                                    } else {
                                        $values[] = $item;
                                    }
                                }

                                if ($values !== []) {
                                    $q->whereIn('source', $values, 'or');
                                }
                            } else {
                                $q->where('source', self::CABINET_SOURCE_UTMS[$cabinet]);
                            }
                            return $q;
                        });
                    }
                    return $q;
                });
            }

            if (!empty($filters['seo']) && $filters['seo']['enabled']) {
                $q->orWhere('traffic_source', 'organic');
                // TODO: Добавить фильтры по поисковым движкам
            }
        });

        return $q;
    }

    /**
     * @inheritDoc
     */
    public function getCampaignUtms(): array
    {
        return $this->getColUniqValues('campaign');
    }

    /**
     * @inheritDoc
     */
    public function getMediumUtms(): array
    {
        return $this->getColUniqValues('medium');
    }

    /**
     * @inheritDoc
     */
    public function getSourceUtms(): array
    {
        return $this->getColUniqValues('source');
    }

    private function getColUniqValues(string $colName): array
    {
        $settings_id = $this->source->settings_id;
        $f = static fn(string $table): \Illuminate\Database\Query\Builder => DB::table($table)
            ->select($colName)->distinct()
            ->whereNotNull($colName)
            ->where('settings_id', $settings_id);

        $q = DB::query()
            ->select($colName)
            ->distinct()
            ->from($f('metrika_conversions')->union($f('metrika_visits')));

        return $q->get()
            ->pluck($colName)
            ->toArray();
    }

    /**
     * @inheritDoc
     */
    public function isEcommerceEnabled(): bool
    {
        return (bool)$this->source->settings->ecommerce;
    }
}
