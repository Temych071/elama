<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika\Actions\Fetch;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Module\DgMarks\Services\DgMarksParser;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexMetrika\Exceptions\YandexMetrikaException;
use Module\Source\YandexMetrika\Models\MetrikaSourceSettings;
use Module\Source\YandexMetrika\YandexMetrikaService;

final class FetchEcommerceAction
{
    public function execute(Source $source, Carbon $fromDate): void
    {
        $service = new YandexMetrikaService($source->authToken);

        /** @var MetrikaSourceSettings $settings */
        $settings = $source->settings;

        if (!$settings->ecommerce) {
            return;
        }

        try {
            $res = $service->getEcommerce(
                counterId: $settings->counter_id,
                from: $fromDate->format('Y-m-d'),
            );
        } catch (YandexMetrikaException $e) {
            if (str_contains($e->getMessage(), 'ecommerce is not enabled')) {
                $settings->disableEcommerce();
                return;
            }
            throw $e;
        }

        $res = array_chunk($res, 500);
        $now = Carbon::now();
        $dgMarksParser = app(DgMarksParser::class);

        foreach ($res as $chunk) {
            $data = array_map(static fn ($row): array => [
                ...$row,
                ...$dgMarksParser->parseFromUrl($row['start_url'] ?? '')?->toArray() ?? [],
                'settings_id' => $settings->id,
                'uniq_hash' => self::generateUniqueHash($settings, $row),
                'created_at' => $now,
            ], $chunk);

            DB::table('yandex_metrika_ecommerce')
                ->upsert(
                    values: $data,
                    uniqueBy: ['uniq_hash'],
                    update: [
                        'ecommerce_purchases',
                        'ecommerce_revenue',
                        'product_baskets_price',
                        'product_baskets_quantity',
                        'product_impressions',
                        'product_purchased_price',
                        'product_purchased_quantity',
                        'visits',
                    ],
                );
        }
    }

    /**
     * @param  mixed  $row
     */
    private static function generateUniqueHash(MetrikaSourceSettings $settings, array $row): string|false
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
