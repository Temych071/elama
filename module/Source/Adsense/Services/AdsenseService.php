<?php

declare(strict_types=1);

namespace Module\Source\Adsense\Services;

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\V10\Services\CustomerServiceClient;
use Google\Ads\GoogleAds\V10\Services\GoogleAdsRow;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Module\Source\Adsense\Services\Data\CampaignName;

final class AdsenseService
{
    protected GoogleAdsClient $client;

    public function __construct(string $refreshToken)
    {
        $this->client = (new GoogleAdsClientBuilder())
            ->withDeveloperToken(config('services.source_google_ads.dev_token'))
            ->withOAuth2Credential(
                oAuth2Credential: (new OAuth2TokenBuilder())
                    ->withClientId(config('services.source_google_ads.client_id'))
                    ->withClientSecret(config('services.source_google_ads.client_secret'))
                    ->withRefreshToken($refreshToken)
                    ->build(),
            )
            ->build();
    }


    /**
     * Retrieves a list of accessible customers with the provided set up credentials.
     *
     * @return int[] the list of customer IDs
     * @throws ApiException
     * @throws ValidationException
     */
    public function getAccessibleCustomerIds(): array
    {
        $accessibleCustomerIds = [];
        $accessibleCustomers = $this->client->getCustomerServiceClient()->listAccessibleCustomers();

        foreach ($accessibleCustomers->getResourceNames() as $customerResourceName) {
            $customer = CustomerServiceClient::parseName($customerResourceName)['customer_id'];
            $accessibleCustomerIds[] = (int)$customer;
        }

        return $accessibleCustomerIds;
    }

    /**
     * @throws ApiException
     * @throws ValidationException
     */
    public function getCampaigns(int $account): array
    {
        $googleAdsServiceClient = $this->client->getGoogleAdsServiceClient();

        $query = "
            SELECT campaign.id, campaign.name
            FROM campaign
            WHERE campaign.status IN ('ENABLED', 'PAUSED')
            ORDER BY campaign.id
        ";

        // todo report
//        $query = "
//        SELECT
        //  segments.date,
        //  metrics.impressions,
        //  metrics.clicks,
        //  metrics.bounce_rate,
        //  metrics.average_cost,
        //  ad_group.id,
        //  ad_group.name,
        //  ad_group.campaign,
        //  campaign.id,
        //  campaign.advertising_channel_type,
        //  metrics.average_page_views,
        //  segments.keyword.info.text
        //FROM ad_group_ad
        // WHERE  segments.date > '2021-01-01'
        //  AND segments.date < '2022-02-01'
//        ";
        $stream = $googleAdsServiceClient->search($account, $query);
        //dd(iterator_to_array($stream->iterateAllElements(), true));
        $items = [];

        foreach ($stream->iterateAllElements() as $googleAdsRow) {
            /** @var GoogleAdsRow $googleAdsRow */
            $items[] = new CampaignName(
                id: $googleAdsRow->getCampaign()->getId(),
                name: $googleAdsRow->getCampaign()->getName(),
            );
        }

        return $items;
    }
}
