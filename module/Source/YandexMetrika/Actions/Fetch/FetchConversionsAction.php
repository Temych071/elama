<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika\Actions\Fetch;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Module\DgMarks\Services\DgMarksParser;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexMetrika\Models\MetrikaSourceSettings;
use Module\Source\YandexMetrika\YandexMetrikaService;

final class FetchConversionsAction
{
    public function execute(Source $source, Carbon $fromDate): void
    {
        $service = new YandexMetrikaService($source->authToken);

        /** @var MetrikaSourceSettings $settings */
        $settings = $source->settings;

        $res = $service->getConversions(
            counterId: $settings->counter_id,
            from: $fromDate->format('Y-m-d'),
        );
        $res = array_chunk($res, 500);

        $dgMarksParser = app(DgMarksParser::class);
        $now = Carbon::now();
        foreach ($res as $chunk) {
            $data = array_map(static fn ($row): array => [
                ...$row,
                ...$dgMarksParser->parseFromUrl($row['start_url'] ?? '')?->toArray() ?? [],
                'settings_id' => $settings->id,
                'uniq_hash' => self::generateUniqueHash($settings, $row),
                'created_at' => $now,
            ], $chunk);

            DB::table('metrika_conversions')
                ->upsert(
                    values: $data,
                    uniqueBy: ['uniq_hash'],
                    update: ['reaches'],
                );
        }
    }

    private static function generateUniqueHash(MetrikaSourceSettings $settings, mixed $row): string|false
    {
        return hash(
            'sha256',
            $settings->id .
            $row['goal_id'] .
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
