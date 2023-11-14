<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Services;

use Google\Service\AnalyticsReporting;
use Google\Service\AnalyticsReporting\DateRange;
use Google\Service\AnalyticsReporting\Dimension;
use Google\Service\AnalyticsReporting\GetReportsRequest;
use Google\Service\AnalyticsReporting\Metric;
use Google\Service\AnalyticsReporting\ReportRequest;
use Illuminate\Support\Carbon;

final class AnalyticsVisitsUAService
{
    public function __construct(
        private readonly string $viewId,
        private readonly AnalyticsReporting $analytics,
    ) {
    }

    public function getVisits(Carbon $from): array
    {
        $body = new GetReportsRequest();
        $body->setReportRequests($this->buildVisitsRequest($from));

        $data = $this->analytics->reports->batchGet($body);

        $report = $data->getReports()[0];

        $result = [];

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

            $result[] = [
                'sessions' => $metrics['ga:sessions'],
                'bounces' => $metrics['ga:bounces'],
                'session_duration' => $metrics['ga:sessionDuration'],
                'page_views' => $metrics['ga:pageviews'],
                'organic_searches' => $metrics['ga:organicSearches'],
                'new_users' => $metrics['ga:newUsers'],

                'date' => $dimensions['ga:date'],
                'campaign' => $dimensions['ga:campaign'],
                'source' => $dimensions['ga:source'],
                'medium' => $dimensions['ga:medium'],
                'keyword' => $dimensions['ga:keyword'],
                'device_category' => $dimensions['ga:deviceCategory'],
                'hostname' => $dimensions['ga:hostname'],
                'url_path' => $dimensions['ga:pagePath'],
            ];
        }

        return $result;
    }

    private function buildVisitsRequest(Carbon $from): ReportRequest
    {
        $dateRange = new DateRange();
        $dateRange->setStartDate($from->format('Y-m-d'));
        $dateRange->setEndDate(Carbon::now()->format('Y-m-d'));

        $dimension = array_map(static function ($name): \Google\Service\AnalyticsReporting\Dimension {
            $dimension = new Dimension();
            $dimension->setName($name);
            return $dimension;
        }, ['ga:date', 'ga:campaign', 'ga:source', 'ga:medium', 'ga:keyword', 'ga:deviceCategory', 'ga:hostname', 'ga:pagePath',]);

        $metrics = array_map(static function ($name): \Google\Service\AnalyticsReporting\Metric {
            $metric = new Metric();
            $metric->setExpression($name);
            return $metric;
        }, ['ga:sessions', 'ga:bounces', 'ga:sessionDuration', 'ga:pageviews', 'ga:organicSearches', 'ga:newUsers']);

        $request = new ReportRequest();
        $request->setViewId($this->viewId);
        $request->includeEmptyRows = true;
        $request->setPageSize(100000);
        $request->setDimensions($dimension);
        $request->setDateRanges($dateRange);
        $request->setMetrics($metrics);

        return $request;
    }
}
