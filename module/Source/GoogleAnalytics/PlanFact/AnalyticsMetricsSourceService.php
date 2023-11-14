<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\PlanFact;

use App\Infrastructure\DateRange;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
use Module\PlanFact\Contracts\MetricsSourceService;
use Module\Source\GoogleAnalytics\Data\AnalyticsGoalData;
use Module\Source\Sources\Models\Source;

final class AnalyticsMetricsSourceService implements MetricsSourceService
{
    public function __construct(
        private readonly Source $source,
    ) {
    }

    #[ArrayShape([['date' => Carbon::class, 'requests' => "int"]])]
    public function getConversions(DateRange $period, ?array $filters = null): array
    {
        return $this->newQuery($period, $filters)
            ->selectRaw('date, SUM(completions) AS completions')
            ->groupBy('date')
            ->get()
            ->map(static fn ($item): array => [
                'date' => $item->date,
                'requests' => $item->completions,
            ])
            ->toArray();
    }

    /**
     * @return string[]
     */
    public function getDomains(?DateRange $period = null, ?array $filters = null): array
    {
        return $this->newQuery($period, $filters)
            ->select(['hostname'])
            ->distinct()
            ->get()
            ->map(static fn ($item) => $item->hostname)
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

    private function newQuery(?DateRange $period, ?array $filters = null): Builder
    {
        $q = DB::table('analytics_conversions')
            ->where('settings_id', $this->source->settings_id);

        if (!is_null($period)) {
            $q->whereDate('date', '>=', $period->getFrom())
                ->whereDate('date', '<=', $period->getTo());
        }

        if (!is_null($filters)) {
            $q = $this->applyFilters($q, $filters);
        }

        return $q;
    }

    private const SIMPLE_FILTERS = [
        /* filter_key => col */
        'device' => 'device_category',
        'domain' => 'hostname',
    ];

    private function applyFilters(Builder $q, array $filters): Builder
    {
        foreach (self::SIMPLE_FILTERS as $key => $col) {
            if (!empty($filters[$key])) {
                $q->where($col, $filters[$key]);
            }
        }

        if (!empty($filters['goals'])) {
            $q->whereIn('goal_id', $filters['goals']);
        } else {
            $q->whereIn(
                'goal_id',
                $this->source
                    ->settings
                    ->goals
                    ->toCollection()
                    ->map(static fn (AnalyticsGoalData $goal): int => $goal->id)
                    ->toArray(),
            );
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

        if (!empty($filters['cabinets'])) {
            $cabinets = $filters['cabinets'];
            $q->where(static function (Builder $q) use ($cabinets): \Illuminate\Database\Query\Builder {
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
                        $q->where('medium', 'cpc');
                        return $q;
                    });
                }
                return $q;
            });

            $q->where(static function (Builder $q): \Illuminate\Database\Query\Builder {
                foreach (['cpc', 'cpm'] as $medium) {
                    $q->orWhere('medium', 'LIKE', "$medium%");
                }
                return $q;
            });
        }

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
            ->from($f('analytics_conversions')->union($f('analytics_visits')));

        return $q->get()
            ->pluck($colName)
            ->toArray();
    }
}
