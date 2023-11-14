<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Actions\Fetch;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Module\DgMarks\Services\DgMarksParser;
use Module\Source\GoogleAnalytics\Models\AnalyticsSettings;
use Module\Source\GoogleAnalytics\Services\GoogleAnalyticsService;
use Module\Source\Sources\Models\Source;

final class FetchConversionsAction
{
    public function __construct(
        private readonly GoogleAnalyticsService $analyticsService,
    ) {
    }

    public function execute(Source $source, Carbon $fromDate): void
    {
        /** @var AnalyticsSettings $settings */
        $settings = $source->settings;

        $conversions = $this->analyticsService
            ->connectUsing($source->authToken)
            ->getConversionReportService($settings->view_id)
            ->getConversions($settings->goals->toCollection(), $fromDate);

        $conversions = array_chunk($conversions, 500);

        $now = Carbon::now();
        $dgMarksParser = app(DgMarksParser::class);

        foreach ($conversions as $batch) {
            $data = array_map(static function ($row) use ($now, $settings, $dgMarksParser): array {
                $hash = hash('sha256', $settings->id .
                    $row['goal_id'] .
                    $row['date'] .
                    $row['campaign'] .
                    $row['source'] .
                    $row['medium'] .
                    $row['keyword'] .
                    $row['device_category'] .
                    $row['url_path'] .
                    $row['hostname']);

                return [
                    ...$row,
                    ...$dgMarksParser->parseFromUrl($row['url_path'] ?? '')?->toArray() ?? [],

                    'settings_id' => $settings->id,
                    'uniq_hash' => $hash,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }, $batch);

            DB::table('analytics_conversions')
                ->upsert(
                    values: $data,
                    uniqueBy: ['uniq_hash'],
                    update: ['completions', 'conversion_rate'],
                );
        }
    }
}
