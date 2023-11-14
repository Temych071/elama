<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika;

use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Module\Source\Sources\Models\SourceAuthToken;
use Module\Source\YandexDirect\Actions\RefreshYandexAuthTokenAction;
use Module\Source\YandexMetrika\Data\CounterData;
use Module\Source\YandexMetrika\Exceptions\YandexMetrikaException;
use Spatie\LaravelData\DataCollection;

final class YandexMetrikaService
{
    private const DOMAIN = 'https://api-metrika.yandex.net/';
    private const GOAL_METRICS_PREFIX = 'ym:s:goal';
    public const METRICS_LIMIT = 20;

    public function __construct(
        private SourceAuthToken $token,
    ) {
    }

    /**
     * @return DataCollection
     * @throws BusinessException
     * @throws YandexMetrikaException
     */
    public function getCounters(): DataCollection
    {
        $response = $this->get('management/v1/counters', [
            'field' => 'goals',
        ])['counters'];

        $response = array_map(
            static fn ($counter) => array_merge($counter, [
                'ecommerce' => (bool)$counter['code_options']['ecommerce'],
                'name' => empty($counter['name']) ? $counter['site'] : $counter['name'],
            ]),
            $response
        );

        return CounterData::collection($response);
    }

    public function getGoalsList(int $counter): ?array
    {
        $response = $this->get("management/v1/counter/$counter/goals");

        return $response['goals'];
    }

    private static function makeGoalMetric(int $goalId, string $type): string
    {
        return self::GOAL_METRICS_PREFIX . "$goalId$type";
    }

    /**
     * @param  int[]|null  $goalIds
     * @param  string[]  $types
     * @return string[]
     */
    #[Pure]
    private static function makeGoalsMetrics(?array $goalIds, array $types): array
    {
        if (empty($goalIds)) {
            return ['ym:s:anyGoalConversionRate', 'ym:s:sumGoalReachesAny'];
        }

        $metrics = [];
        foreach ($types as $type) {
            foreach ($goalIds as $goalId) {
                $metrics[] = self::makeGoalMetric($goalId, $type);
            }
        }

        return $metrics;
    }

    private static function resolveDimensionNames(array $dimensions): array
    {
        return array_map(static fn ($item) => [
                'ym:s:date' => 'date',
                'ym:s:startURL' => 'start_url',
                'ym:s:lastsignUTMCampaign' => 'campaign',
//                'ym:s:lastsignUTMContent' => 'content',
                'ym:s:lastsignUTMMedium' => 'medium',
                'ym:s:lastsignUTMSource' => 'source',
                'ym:s:lastsignUTMTerm' => 'term',
                'ym:s:regionCity' => 'city',
                'ym:s:deviceCategory' => 'device_category',
                'ym:s:lastsignTrafficSource' => 'traffic_source',
                'ym:s:lastsignSearchEngine' => 'search_engine',
            ][$item] ?? $item, $dimensions);
    }

    private static function resolveMetricNames(array $metrics): array
    {
        return array_map(static fn ($item) => [
                'ym:s:visits' => 'visits',
                'ym:s:pageviews' => 'page_views',
                'ym:s:bounceRate' => 'bounce_rate',
                'ym:s:pageDepth' => 'page_depth',
                'ym:s:avgVisitDurationSeconds' => 'avg_visit_duration',
                'ym:s:users' => 'users',
                'ym:s:newUsers' => 'new_users',

                'ym:s:ecommerceRevenue' => 'ecommerce_revenue',
                'ym:s:ecommercePurchases' => 'ecommerce_purchases',

                'ym:s:productImpressions' => 'product_impressions',
                'ym:s:productBasketsQuantity' => 'product_baskets_quantity',
                'ym:s:productBasketsPrice' => 'product_baskets_price',
                'ym:s:productPurchasedPrice' => 'product_purchased_price',
                'ym:s:productPurchasedQuantity' => 'product_purchased_quantity',
            ][$item] ?? $item, $metrics);
    }

    private static function readValues(array $names, array $values): array
    {
        $res = [];
        foreach ($names as $i => $name) {
            $res[$name] = is_string($values[$i])
                ? mb_substr($values[$i], 0, 255)
                : $values[$i] ?? 0;
        }
        return $res;
    }

    private static function resolveGoalNames(array $goals): array
    {
        return array_map(static function ($goal): array {
            // 'ym:s:goal16437860reaches' -> ['ym:s:goal16437860reaches', 16437860, 'reaches']
            preg_match('/^ym:s:goal(\d+)(\D.*)$/iu', $goal, $data);

            return [
                'id' => (int)$data[1],
                'type' => match ($data[2]) {
//                    'reaches' => 'reaches',
                    'conversionRate' => 'conversion_rate',
                    default => $data[2],
                },
            ];
        }, $goals);
    }

    /**
     * @param  string[]  $names
     * @param  array[]  $values
     */
    private static function readDimensionValues(array $names, array $values): array
    {
        $res = [];
        foreach ($names as $i => $name) {
            $res[$name] = match ($name) {
                'device_category', 'traffic_source' => $values[$i]['id'],
                default => $values[$i]['name'],
            };

            if (is_string($res[$name])) {
                $res[$name] = mb_substr($res[$name], 0, 250);
            }
        }
        return $res;
    }

    public function getConversions(int $counterId, string $from, string $to = 'today'): array
    {
        $goals = array_column($this->getGoalsList($counterId), 'id');

        $chunks = array_chunk($goals, self::METRICS_LIMIT);

        $data = [];
        foreach ($chunks as $chunk) {
            $metrics = self::makeGoalsMetrics($chunk, ['reaches']);

            $res = $this->getStatData($counterId, $from, $to, $metrics);

            $dimensions = self::resolveDimensionNames($res['query']['dimensions']);
            $metrics = self::resolveGoalNames($res['query']['metrics']);

            foreach ($res['data'] as $resItem) {
                $d = self::readDimensionValues($dimensions, $resItem['dimensions']);

                $goalsData = [];
                foreach ($metrics as $i => $metric) {
                    $goalsData[$metric['id']][$metric['type']] = $resItem['metrics'][$i];
                }

                foreach ($goalsData as $goalId => $goalData) {
                    if ($goalData['reaches'] > 0) {
                        $data[] = array_merge($d, $goalData, ['goal_id' => $goalId]);
                    }
                }
            }
        }

        return $data;
    }

    public function getEcommerce(int $counterId, string $from, string $to = 'today'): array
    {
        $res = $this->getStatData($counterId, $from, $to, [
            'ym:s:visits',

            'ym:s:ecommerceRevenue',
            'ym:s:ecommercePurchases',

            'ym:s:productImpressions',
            'ym:s:productBasketsQuantity',
            'ym:s:productBasketsPrice',
            'ym:s:productPurchasedPrice',
            'ym:s:productPurchasedQuantity',
        ]);

        return self::prepareStatResponse($res);
    }

    public function getVisits(int $counterId, string $from, string $to = 'today'): array
    {
        $res = $this->getStatData($counterId, $from, $to);

        return self::prepareStatResponse($res);
    }

    private static function prepareStatResponse(array $res): array
    {
        $dimensions = self::resolveDimensionNames($res['query']['dimensions']);
        $metrics = self::resolveMetricNames($res['query']['metrics']);

        $data = [];
        foreach ($res['data'] as $resItem) {
            $d = self::readDimensionValues($dimensions, $resItem['dimensions']);
            $m = self::readValues($metrics, $resItem['metrics']);

            $data[] = array_merge($d, $m);
        }
        return $data;
    }

    private function getStatData(
        int $counterId,
        string $from,
        string $to = 'today',
        array $metrics = [
            'ym:s:visits',
            'ym:s:pageviews',
            'ym:s:bounceRate',
            'ym:s:pageDepth',
            'ym:s:avgVisitDurationSeconds',
            'ym:s:users',
            'ym:s:newUsers'
        ],
        array $dimensions = [
            'ym:s:date',
            'ym:s:startURL',
            'ym:s:lastsignUTMCampaign',
            'ym:s:lastsignUTMMedium',
            'ym:s:lastsignUTMSource',
            'ym:s:lastsignUTMTerm',
            'ym:s:regionCity',
            'ym:s:deviceCategory',
            'ym:s:lastsignTrafficSource',
            'ym:s:lastsignSearchEngine'
        ],
        string $sort = 'ym:s:date',
    ): array {
        return $this->get('stat/v1/data', [
            'ids' => $counterId,

            'date1' => $from,
            'date2' => $to,

            'metrics' => implode(',', $metrics),
            'dimensions' => implode(',', $dimensions),
            'sort' => $sort,

            'accuracy' => 'full',
            'group' => 'day',
//            'filters' => 'ym:s:isRobot==\'No\'',
            'timezone' => '+00:00',
            'limit' => 100000,
        ]);
    }

    /**
     * @throws YandexMetrikaException
     */
    private function _GET(string $url, array $query = []): array
    {
        $res = Http::withHeaders($this->getHeaders())
            ->timeout(60)
            ->get(self::DOMAIN . ltrim($url, '/'), $query)
//            ->throw()
            ->json();

        YandexMetrikaException::throwIfError($res);

        return $res;
    }

    #[ArrayShape(['Content-Type' => "string", 'Authorization' => "string"])]
    private function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/x-yametrika+json',
            'Authorization' => 'OAuth ' . $this->token->token,
        ];
    }

    /**
     * @param  string  $url
     * @param  array  $query
     * @return array
     * @throws BusinessException
     * @throws YandexMetrikaException
     */
    private function get(string $url, array $query = []): array
    {
        $this->checkAndRefreshAuthToken();

        try {
            return $this->_GET(...func_get_args());
        } catch (YandexMetrikaException $e) {
            $e->throwIfNotExistType(YandexMetrikaException::INVALID_TOKEN_ERR_TYPE);
        }

        $this->refreshAuthToken();
        if (!$this->token->invalid) {
            try {
                return $this->_GET(...func_get_args());
            } catch (YandexMetrikaException $e) {
                $e->throwIfNotExistType(YandexMetrikaException::INVALID_TOKEN_ERR_TYPE);
            }
        }

        $this->token->makeInvalid();
        throw new BusinessException('Invalid OAuth token for Yandex.Metrika source.');
    }

    private function checkAndRefreshAuthToken(): void
    {
        if ($this->token->isExpired()) {
            $this->refreshAuthToken();
        }
    }

    private function refreshAuthToken(): void
    {
        $this->token = app(RefreshYandexAuthTokenAction::class)->execute($this->token);
    }
}
