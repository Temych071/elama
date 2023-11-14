<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika\Actions\Fetch;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Module\DgMarks\Services\DgMarksParser;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexMetrika\Models\MetrikaSourceSettings;
use Module\Source\YandexMetrika\YandexMetrikaService;

final class FetchVisitsAction
{
    public function execute(Source $source, Carbon $fromDate): void
    {
        $service = new YandexMetrikaService($source->authToken);

        /** @var MetrikaSourceSettings $settings */
        $settings = $source->settings;

        $res = $service->getVisits($settings->counter_id, $fromDate->format('Y-m-d'));
        $res = array_chunk($res, 500);

        $dgMarksParser = app(DgMarksParser::class);

        $now = Carbon::now();
        foreach ($res as $chunk) {
            $data = array_map(static fn ($row): array => [
                ...$row,
                ...$dgMarksParser->parseFromUrl($row['start_url'] ?? '')?->toArray() ?? [],
                'settings_id' => $settings->id,
                'uniq_hash' => self::generateUniqueHash($settings, $row),
                'bounce_rate' => self::fixNegativeValue($row['bounce_rate']),
                'avg_visit_duration' => self::fixNegativeValue($row['avg_visit_duration']),
                'page_views' => abs((int)$row['page_views']),
                'users' => abs((int)$row['users']),
                'new_users' => abs((int)$row['new_users']),
                'created_at' => $now,
            ], $chunk);

            DB::table('metrika_visits')
                ->upsert(
                    values: $data,
                    uniqueBy: ['uniq_hash'],
                    update: [
                        'visits',
                        'page_views',
                        'bounce_rate',
                        'page_depth',
                        'avg_visit_duration',
                        'users',
                        'new_users',
                    ],
                );
        }
    }

    /** метрика почему то отдает bounce rate -100 */
    private static function fixNegativeValue($val): float
    {
        $rate = (float)$val;

        if ($rate < 0) {
            return 0.0;
        }

        return $rate;
    }

    private static function generateUniqueHash(MetrikaSourceSettings $settings, mixed $row): string|false
    {
        return hash(
            'sha256',
            $settings->id .
            $row['date'] .
            $row['start_url'] .
            $row['campaign'] .
            $row['medium'] .
            $row['source'] .
            $row['term'] .
            $row['city'] .
            $row['device_category'] .
            $row['traffic_source'] .
            $row['search_engine']
        );
    }
}
