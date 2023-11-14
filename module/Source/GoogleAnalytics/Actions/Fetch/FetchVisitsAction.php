<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Actions\Fetch;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Module\DgMarks\Services\DgMarksParser;
use Module\Source\GoogleAnalytics\Models\AnalyticsSettings;
use Module\Source\GoogleAnalytics\Services\GoogleAnalyticsService;
use Module\Source\Sources\Models\Source;

final class FetchVisitsAction
{
    public function __construct(
        private readonly GoogleAnalyticsService $analyticsService,
    ) {
    }

    public function execute(Source $source, Carbon $fromDate): void
    {
        /** @var AnalyticsSettings $settings */
        $settings = $source->settings;

        $visits = $this->analyticsService
            ->connectUsing($source->authToken)
            ->getVisitsReportService($settings->view_id)
            ->getVisits($fromDate);

        $visits = array_chunk($visits, 500);

        $now = Carbon::now();
        $dgMarksParser = app(DgMarksParser::class);

        foreach ($visits as $batch) {
            $data = array_map(static fn ($row): array => [
                ...$row,
                ...$dgMarksParser->parseFromUrl($row['url_path'] ?? '')->toArray(),

                'settings_id' => $settings->id,
                'uniq_hash' => self::generateUniqueHash($settings, $row),
                'created_at' => $now,
                'updated_at' => $now,
            ], $batch);

            DB::table('analytics_visits')
                ->upsert(
                    values: $data,
                    uniqueBy: ['uniq_hash'],
                    update: ['sessions', 'bounces', 'session_duration', 'page_views', 'organic_searches', 'new_users'],
                );
        }
    }

    private static function generateUniqueHash(AnalyticsSettings $settings, mixed $row): string|false
    {
        return hash('sha256', $settings->id .
            $row['date'] .
            $row['campaign'] .
            $row['source'] .
            $row['medium'] .
            $row['keyword'] .
            $row['device_category'] .
            $row['url_path'] .
            $row['hostname']);
    }
}
