<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Services;

final class MetricsAdder
{
    private const SUM_METRICS = ['clicks', 'requests', 'expenses', 'income', 'purchases', 'impressions'];

    /**
     * @param  array<string, float|int>[]  $metricsArray
     * @return array<string, float|int>
     */
    public function sumArray(...$metricsArray): array
    {
        if (is_null($metricsArray[0])) {
            return [];
        }

        if (array_is_list($metricsArray[0])) {
            $metricsArray = $metricsArray[0];
        }

        $res = [];
        foreach ($metricsArray as $metrics) {
            $this->sum($res, $metrics);
        }
        return $res;
    }

    /**
     * @param  array<string, float|int>  &$metrics
     * @param  ?array<string, float|int>  $add
     * @return array<string, float|int>
     */
    public function sum(array &$metrics, ?array $add): array
    {
        if (is_null($add)) {
            return $metrics;
        }

        foreach (self::SUM_METRICS as $metric) {
            if (isset($add[$metric])) {
                $metrics[$metric] = isset($metrics[$metric])
                    ? $metrics[$metric] + $add[$metric]
                    : $add[$metric];
            }
        }
        return $metrics;
    }
}
