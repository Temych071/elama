<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Services\Conversions;

use Google\Service\AnalyticsReporting;
use Google\Service\AnalyticsReporting\DateRange;
use Google\Service\AnalyticsReporting\Dimension;
use Google\Service\AnalyticsReporting\GetReportsRequest;
use Google\Service\AnalyticsReporting\Metric;
use Google\Service\AnalyticsReporting\ReportRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Module\Source\GoogleAnalytics\Data\AnalyticsGoalData;

final class AnalyticsConversionReportUAService implements AnalyticsConversionReportService
{
    private const METRICS_PER_REQUEST_LIMIT = 10;

    public function __construct(
        private readonly string $viewId,
        private readonly AnalyticsReporting $analytics,
    ) {
    }

    public function getConversions(Collection $goals, Carbon $from): array
    {
        $requests = $this->buildConversionRequest($from, $goals);

        $body = new GetReportsRequest();
        $body->setReportRequests($requests);

        $data = $this->analytics
            ->reports
            ->batchGet($body);

        $result = [];

        foreach ($data->getReports() as $report) {
            $dimensionNames = $report->getColumnHeader()->getDimensions();
            $metricNames = array_map(static fn($metric) => $metric->getName(), $report->getColumnHeader()->getMetricHeader()->getMetricHeaderEntries());

            $rows = $report->getData()->getRows();

            foreach ($rows as $row) {
                $dimensions = array_combine($dimensionNames, $row->getDimensions());

                foreach ($dimensions as $key => $val) {
                    if ($val === '(none)' || $val === '(not set)') {
                        $dimensions[$key] = null;
                    }
                }

                $metrics = array_combine($metricNames, $row->getMetrics()[0]->getValues());

                $temp = array_chunk($metrics, 2, true);

                foreach ($temp as $item) {
                    [, $goalId] = explode('-', array_key_first($item));

                    $values = array_values($item);

                    $result[] = [
                        'goal_id' => $goalId,
                        'completions' => (int)$values[0],
                        'conversion_rate' => (float)$values[1],
                        'date' => $dimensions['ga:date'],
                        'campaign' => $dimensions['ga:campaign'],
                        'source' => $dimensions['ga:source'],
                        'medium' => $dimensions['ga:medium'],
                        'keyword' => $dimensions['ga:keyword'],
                        'device_category' => $dimensions['ga:deviceCategory'],
                        'url_path' => $dimensions['ga:pagePath'],
                        'hostname' => $dimensions['ga:hostname'],
                    ];
                }
            }
        }

        return $result;
    }


    /**
     * @param Collection<AnalyticsGoalData> $goals
     * @return Collection<ReportRequest>
     */
    private function buildConversionRequest(Carbon $from, Collection $goals): Collection
    {
        $viewId = $this->viewId;

        $dateRange = new DateRange();
        $dateRange->setStartDate($from->format('Y-m-d'));
        $dateRange->setEndDate(Carbon::now()->format('Y-m-d'));

        $dimension = array_map(static function ($name): \Google\Service\AnalyticsReporting\Dimension {
            $dimension = new Dimension();
            $dimension->setName($name);
            return $dimension;
        }, ['ga:date', 'ga:campaign', 'ga:source', 'ga:medium', 'ga:keyword', 'ga:deviceCategory', 'ga:hostname', 'ga:pagePath',]);

        return $this->chunkGoals($goals)
            ->map(static function (Collection $metrics) use ($viewId, $dimension, $dateRange): \Google\Service\AnalyticsReporting\ReportRequest {
                $request = new ReportRequest();
                $request->setViewId($viewId);
                $request->includeEmptyRows = true;
                $request->setPageSize(100000);
                $request->setDimensions($dimension);
                $request->setDateRanges($dateRange);
                $request->setMetrics($metrics->values());

                return $request;
            });
    }

    /**
     * @param Collection<AnalyticsGoalData> $goals
     * @return Collection<ReportRequest>
     */
    private function chunkGoals(Collection $goals): Collection
    {
        return $goals
            ->flatMap(function (AnalyticsGoalData $goal): array {
                $id = $goal->id;

                $completions = new Metric();
                $completions->setExpression("ga:goal${id}Completions");
                $completions->setAlias("completions-$id");

                $conversionRate = new Metric();
                $conversionRate->setExpression("ga:goal${id}ConversionRate");
                $conversionRate->setAlias("conversionRate-$id");

                return [$completions, $conversionRate];
            }) // необходимо следить за тем, чтобы метрики одной конверсии не разбивались на разные запросы
            ->chunk(self::METRICS_PER_REQUEST_LIMIT);
    }
}
