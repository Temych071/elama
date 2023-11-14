<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Services;

use App\Exceptions\BusinessException;
use App\Infrastructure\DateRange;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\ArrayShape;
use Module\Source\Sources\Models\SourceAuthToken;
use Module\Source\YandexDirect\Actions\RefreshYandexAuthTokenAction;
use Module\Source\YandexDirect\Exceptions\YandexDirectException;
use Module\Source\YandexDirect\Exceptions\YandexDirectInvalidOAuthTokenException;
use Module\Source\YandexDirect\Exceptions\YandexDirectReportNotReadyException;
use stdClass;

use function config;
use function env;

final class YandexDirectService
{
    public int $latestStatus;

    public const CAMPAIGN_TYPE_FIELDS_KEY = [
        'TEXT_CAMPAIGN' => 'TextCampaign',
        'SMART_CAMPAIGN' => 'SmartCampaign',
        'DYNAMIC_TEXT_CAMPAIGN' => 'DynamicTextCampaign',
        'MOBILE_APP_CAMPAIGN' => 'MobileAppCampaign',
        'CPM_BANNER_CAMPAIGN' => 'CpmBannerCampaign',
        'MCBANNER_CAMPAIGN' => null,
        'CPM_DEALS_CAMPAIGN' => null,
        'CPM_FRONTPAGE_CAMPAIGN' => null,
        'CPM_PRICE' => null,
    ];

    public const CAMPAIGN_TYPE_FIELDS_LIST = [
        'FieldNames' => [
            'BlockedIps',
            'ExcludedSites',
            'Currency',
            'DailyBudget',
            'Notification',
            'EndDate',
            'Funds',
            'ClientInfo',
            'Id',
            'Name',
            'NegativeKeywords',
            'RepresentedBy',
            'StartDate',
            'Statistics',
            'State',
            'Status',
            'StatusPayment',
            'StatusClarification',
            'SourceId',
            'TimeTargeting',
            'TimeZone',
            'Type',
        ],
        'TextCampaignFieldNames' => [
            'Settings',
            'CounterIds',
            'RelevantKeywords',
            'BiddingStrategy',
            'PriorityGoals',
            'AttributionModel',
        ],
        'SmartCampaignFieldNames' => [
            'CounterId',
            'Settings',
            'BiddingStrategy',
            'PriorityGoals',
            'AttributionModel',
        ],
        'DynamicTextCampaignFieldNames' => [
            'CounterIds',
            'Settings',
            'PlacementTypes',
            'BiddingStrategy',
            'PriorityGoals',
            'AttributionModel',
        ],
        'MobileAppCampaignFieldNames' => [
            'Settings',
            'BiddingStrategy',
        ],
        'CpmBannerCampaignFieldNames' => [
            'CounterIds',
            'FrequencyCap',
            'VideoTarget',
            'Settings',
            'BiddingStrategy',
        ],
    ];

    public const AD_TYPE_FIELDS_KEY_LIST = [
        'TextAd',
        'DynamicTextAd',
        'MobileAppAd',
        'TextImageAd',
        'MobileAppImageAd',
        'TextAdBuilderAd',
        'MobileAppAdBuilderAd',
        'MobileAppCpcVideoAdBuilderAd',
        'CpmBannerAdBuilderAd',
        'CpcVideoAdBuilderAd',
        'CpmVideoAdBuilderAd',
        'SmartAdBuilderAd',
    ];

    public const AD_TYPE_FIELDS_LIST = [
        'FieldNames' => [
            'AdCategories',
            'AgeLabel',
            'AdGroupId',
            'CampaignId',
            'Id',
            'State',
            'Status',
            'StatusClarification',
            'Type',
            'Subtype',
        ],
        'TextAdFieldNames' => [
            'AdImageHash',
            'DisplayDomain',
            'Href',
            'SitelinkSetId',
            'Text',
            'Title',
            'Title2',
            'Mobile',
            'VCardId',
            'DisplayUrlPath',
            'AdImageModeration',
            'SitelinksModeration',
            'VCardModeration',
            'AdExtensions',
            'DisplayUrlPathModeration',
            'VideoExtension',
            'TurboPageId',
            'TurboPageModeration',
            'BusinessId',
        ],
        'TextAdPriceExtensionFieldNames' => [
            'Price',
            'OldPrice',
            'PriceCurrency',
            'PriceQualifier',
        ],
        'MobileAppAdFieldNames' => [
            'AdImageHash',
            'AdImageModeration',
            'Title',
            'Text',
            'Features',
            'Action',
            'TrackingUrl',
            'VideoExtension',
        ],
        'DynamicTextAdFieldNames' => [
            'AdImageHash',
            'SitelinkSetId',
            'Text',
            'VCardId',
            'AdImageModeration',
            'SitelinksModeration',
            'VCardModeration',
            'AdExtensions',
        ],
        'TextImageAdFieldNames' => [
            'AdImageHash',
            'Href',
            'TurboPageId',
            'TurboPageModeration',
        ],
        'MobileAppImageAdFieldNames' => [
            'AdImageHash',
            'TrackingUrl',
        ],
        'TextAdBuilderAdFieldNames' => [
            'Creative',
            'Href',
            'TurboPageId',
            'TurboPageModeration',
        ],
        'MobileAppAdBuilderAdFieldNames' => [
            'Creative',
            'TrackingUrl',
        ],
        'MobileAppCpcVideoAdBuilderAdFieldNames' => [
            'Creative',
            'TrackingUrl',
        ],
        'CpcVideoAdBuilderAdFieldNames' => [
            'Creative',
            'Href',
            'TurboPageId',
            'TurboPageModeration',
        ],
        'CpmBannerAdBuilderAdFieldNames' => [
            'Creative',
            'Href',
            'Href',
            'TurboPageId',
            'TurboPageModeration',
        ],
        'CpmVideoAdBuilderAdFieldNames' => [
            'Creative',
            'Href',
            'Href',
            'TurboPageId',
            'TurboPageModeration',
        ],
        'SmartAdBuilderAdFieldNames' => [
            'Creative',
        ],
    ];

    public const AD_GROUP_TYPE_FIELDS_LIST = [
        'FieldNames' => [
            'CampaignId',
            'Id',
            'Name',
            'NegativeKeywords',
            'NegativeKeywordSharedSetIds',
            'RegionIds',
            'RestrictedRegionIds',
            'ServingStatus',
            'Status',
            'Subtype',
            'TrackingParams',
            'Type',
        ],
        'MobileAppAdGroupFieldNames' => [
            'StoreUrl',
            'TargetDeviceType',
            'TargetCarrier',
            'TargetOperatingSystemVersion',
            'AppIconModeration',
            'AppAvailabilityStatus',
            'AppOperatingSystemType',
        ],
        'DynamicTextAdGroupFieldNames' => [
            'DomainUrl',
            'DomainUrlProcessingStatus',
            'AutotargetingCategories',
        ],
        'DynamicTextFeedAdGroupFieldNames' => [
            'Source',
            'FeedId',
            'SourceType',
            'SourceProcessingStatus',
            'AutotargetingCategories',
        ],
        'SmartAdGroupFieldNames' => [
            'FeedId',
            'AdTitleSource',
            'AdBodySource',
        ],
        'TextAdGroupFeedParamsFieldNames' => [
            'FeedId',
            'FeedCategoryIds',
        ],
    ];

    public const AD_GROUP_TYPE_FIELDS_KEY_LIST = [
        'MobileAppAdGroup',
        'DynamicTextAdGroup',
        'DynamicTextFeedAdGroup',
        'SmartAdGroup',
        'TextAdGroupFeedParams',
    ];

    public const KEYWORD_FIELDS_LIST = [
//        'Id',
        'Keyword',
//        'State',
//        'Status',
//        'ServingStatus',
        'AdGroupId',
        'CampaignId',
//        'Bid',
//        'ContextBid',
//        'StrategyPriority',
//        'UserParam1',
//        'UserParam2',
//        'Productivity',
//        'StatisticsSearch',
//        'StatisticsNetwork',
//        'AutotargetingCategories',
    ];

    public function __construct(
        private SourceAuthToken $token,
    ) {
    }

    /**
     * @throws YandexDirectReportNotReadyException
     */
    public function reports(
        string $name,
        string $type,
        DateRange $dateRange,
        array $fields,
        array $filters = [],
        array $params = [],
        array $headers = [
            'skipReportHeader' => 'true',
            'skipReportSummary' => 'true',
            'processingMode' => 'auto',
        ]
    ): array {
        $res = $this->post(
            url: 'reports',
            query: [
                'params' => [
                    'SelectionCriteria' => [
                        'DateFrom' => $dateRange->getFrom()->format('Y-m-d'),
                        'DateTo' => $dateRange->getTo()->format('Y-m-d'),
                        'Filter' => $filters,
                    ],
                    'ReportName' => $name,
                    'ReportType' => $type,
                    'FieldNames' => $fields,

                    'DateRangeType' => 'CUSTOM_DATE',
                    'Format' => 'TSV',
                    'IncludeVAT' => 'YES',

                    ...$params
                ]
            ],
            headers: $headers,
            asJson: false,
        );

        if (in_array($this->latestStatus, [201, 202])) {
            throw new YandexDirectReportNotReadyException();
        }

        return self::parseReport($res);
    }

    private static function parseReport(string $report): array
    {
        $res = [];

        $lines = explode("\n", $report);
        $header = explode("\t", array_shift($lines));
        array_pop($lines);

        foreach ($lines as $line) {
            $cells = explode("\t", $line);
            $row = [];
            foreach ($header as $i => $key) {
                $row[$key] = $cells[$i];
            }
            $res[] = $row;
        }
        return $res;
    }

    /**
     * @param  string[]  $fields
     */
    public function getCampaigns(
        array|stdClass $criteria = new stdClass(),
        array $fields = ['Id', 'Name'],
        array $params = []
    ): array {
        return $this->campaigns('get', [
            'SelectionCriteria' => $criteria,
            'FieldNames' => $fields,
            ...$params,
        ])['Campaigns'] ?? [];
    }

    private function campaigns(string $method, array $params): array
    {
        return $this->post('campaigns', [
            'method' => $method,
            'params' => $params,
        ]);
    }

    /**
     * @param  string[]  $fields
     */
    public function getAds(
        array|stdClass $criteria = new stdClass(),
        array $fields = ['Id'],
        array $params = []
    ): array {
        return $this->ads('get', [
                'SelectionCriteria' => $criteria,
                'FieldNames' => $fields,
                ...$params,
            ])['Ads'] ?? [];
    }

    private function ads(string $method, array $params): array
    {
        return $this->post('ads', [
            'method' => $method,
            'params' => $params,
        ]);
    }

    public function getKeywords(
        array|stdClass $criteria = new stdClass(),
        array $fields = ['Id', 'Keyword'],
        array $params = []
    ): array {
        return $this->keywords('get', [
                'SelectionCriteria' => $criteria,
                'FieldNames' => $fields,
                ...$params,
            ])['Keywords'] ?? [];
    }

    private function keywords(string $method, array $params): array
    {
        return $this->post('keywords', [
            'method' => $method,
            'params' => $params,
        ]);
    }

    public function getSitelinksSets(
        array $ids,
        array $fields = ['Id', 'Sitelinks'],
        array $params = [],
    ): array {
        return $this->sitelinks('get', [
                'SelectionCriteria' => [
                    'Ids' => $ids,
                ],
                'FieldNames' => $fields,
                ...$params,
            ])['SitelinksSets'] ?? [];
    }

    private function sitelinks(string $method, array $params): array
    {
        return $this->request('sitelinks', $method, $params);
    }

    public function getAudienceTargets(
        array|stdClass $criteria = new stdClass(),
        array $fields = ['Id', 'AdGroupId', 'CampaignId', 'RetargetingListId', 'InterestId', 'ContextBid', 'StrategyPriority', 'State'],
        array $params = [],
    ): array {
        return $this->audiencetargets('get', [
                'SelectionCriteria' => $criteria,
                'FieldNames' => $fields,
                ...$params,
            ])['AudienceTargets'] ?? [];
    }

    private function audiencetargets(string $method, array $params): array
    {
        return $this->request('audiencetargets', $method, $params);
    }

    public function getBidModifiers(
        array|stdClass $criteria = new stdClass(),
        array $fields = ['Id', 'CampaignId', 'AdGroupId', 'Level', 'Type'],
        array $params = [],
    ): array {
//        dd($criteria);
        return $this->bidmodifiers('get', [
                'SelectionCriteria' => $criteria,
                'FieldNames' => $fields,
                ...$params,
            ])['BidModifiers'] ?? [];
    }

    private function bidmodifiers(string $method, array $params): array
    {
        return $this->request('bidmodifiers', $method, $params);
    }

    private function request(string $scope, string $method, array $params): array
    {
        return $this->post($scope, [
            'method' => $method,
            'params' => $params,
        ]);
    }

    /**
     * @param  string  $url
     * @param  array  $query
     * @param  array  $headers
     * @param  bool  $asJson
     * @return array|string
     * @throws BusinessException
     * @throws RequestException
     * @throws YandexDirectException
     */
    private function post(
        string $url,
        array $query = [],
        array $headers = [],
        bool $asJson = true,
    ): array|string
    {
        $this->checkAndRefreshAuthToken();

        try {
            return $this->_post(...func_get_args());
        } catch (YandexDirectInvalidOAuthTokenException) {
        }

        $this->refreshAuthToken();
        if (!$this->token->invalid) {
            try {
                return $this->_post(...func_get_args());
            } catch (YandexDirectInvalidOAuthTokenException) {
            }
        }

        $this->token->makeInvalid();
        throw new BusinessException('Invalid OAuth token for Yandex.Direct source.');
    }

    /**
     * @param  string[]  $fields
     */
    public function getAdGroups(
        array|stdClass $criteria = new stdClass(),
        array $fields = ['Id', 'Name'],
        array $params = []
    ): array {
        return $this->adgroups('get', [
                'SelectionCriteria' => $criteria,
                'FieldNames' => $fields,
                ...$params,
            ])['AdGroups'] ?? [];
    }

    private function adgroups(string $method, array $params): array
    {
        return $this->post('adgroups', [
            'method' => $method,
            'params' => $params,
        ]);
    }

    /**
     * @throws YandexDirectException
     * @throws RequestException
     */
    private function _post(
        string $url,
        array $query = [],
        array $headers = [],
        bool $asJson = true,
    ): array|string {
        $res = Http::withHeaders(array_merge($this->getHeaders(), $headers))
            ->post(env('YANDEX_DIRECT_API_V5_URL', 'https://api.direct.yandex.com/json/v5/') . ltrim($url, '/'), $query)
            ->throw();

        $this->latestStatus = $res->status();

        if ($asJson) {
            $res = $res->json();
            YandexDirectException::throwIfError($res);
            return $res['result'];
        }

        return $res->body();
    }

    #[ArrayShape(['Authorization' => "string", 'Accept-Language' => "string", 'Content-Type' => "string"])]
    private function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept-Language' => config('app.locale'),
            'Authorization' => 'Bearer ' . $this->token->token,
        ];
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
