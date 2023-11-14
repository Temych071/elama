<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Services;

use Illuminate\Support\Arr;

final class MetricsCalculator
{
    /**
     * @param  array<string, float|int>  $metrics
     * @return array<string, float|int>
     */
    public function calcAll(array &$metrics): array
    {
        if (self::has($metrics, ['expenses', 'clicks'])) {
            $metrics['cpc'] = $this->cpc($metrics['expenses'], $metrics['clicks']);
        }
        if (self::has($metrics, ['expenses', 'requests'])) {
            $metrics['cpl'] = $this->cpl($metrics['expenses'], $metrics['requests']);
        }
        if (self::has($metrics, ['clicks', 'requests'])) {
            $metrics['cr'] = $this->cr($metrics['clicks'], $metrics['requests']);
        }
        if (self::has($metrics, ['expenses', 'income'])) {
            $metrics['drr'] = $this->drr($metrics['expenses'], $metrics['income']);
        }
        if (self::has($metrics, ['impressions', 'clicks'])) {
            $metrics['ctr'] = $this->ctr($metrics['impressions'], $metrics['clicks']);
        }

        return $metrics;
    }

    private static function has(array &$metrics, string|array $keys): bool
    {
        foreach (Arr::wrap($keys) as $key) {
            if (is_null($metrics[$key] ?? null)) {
                return false;
            }
        }
        return true;
    }

    public function cpc(float|int $expenses, float|int $clicks): float|int
    {
        if ($clicks <= 0) {
            return 0;
        }
        return round($expenses / $clicks, 2);
    }

    public function cpl(float|int $expenses, float|int $requests): float|int
    {
        if ($requests <= 0) {
            return 0;
        }
        return round($expenses / $requests, 2);
    }

    public function cr(float|int $clicks, float|int $requests): float|int
    {
        if ($clicks <= 0) {
            return 0;
        }
        return round(($requests / $clicks) * 100, 2);
    }

    public function drr(float|int $expenses, float|int $income): float|int
    {
        if ($income <= 0) {
            return 0;
        }
        return round(($expenses / $income) * 100, 2);
    }

    public function ctr(int $impressions, int $clicks): float|int
    {
        if ($impressions <= 0) {
            return 0;
        }
        return round(($clicks / $impressions) * 100, 2);
    }
}
