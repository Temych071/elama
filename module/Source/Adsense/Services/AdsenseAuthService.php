<?php

declare(strict_types=1);

namespace Module\Source\Adsense\Services;

use App\Exceptions\BusinessException;
use Google\Client;
use Module\Source\Sources\Dto\AddAuthTokenDto;
use Module\Source\Sources\Models\Source;

final class AdsenseAuthService
{
    private readonly Client $client;

    public function __construct()
    {
        $client = new Client();
        $client->setClientId(config('services.source_google_ads.client_id'));
        $client->setClientSecret(config('services.source_google_ads.client_secret'));
        $client->setRedirectUri(url(config('services.source_google_ads.redirect')));
        $client->setScopes(config('services.source_google_ads.scopes'));
        $client->setApprovalPrompt(config('services.source_google_ads.approval_prompt'));
        $client->setAccessType(config('services.source_google_ads.access_type'));
        $client->setIncludeGrantedScopes(config('services.source_google_ads.include_granted_scopes'));
        $client->setPrompt('select_account consent');

        $this->client = $client;
    }

    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    /**
     * @throws BusinessException
     */
    public function fetchAuthToken(string $code): AddAuthTokenDto
    {
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($code);

        if (array_key_exists('error', $accessToken)) {
            throw new BusinessException('Не удалось авторизоваться', '/');
        }

        return AddAuthTokenDto::fromGoogle(Source::TYPE_GOOGLE_ADS, $accessToken);
    }
}
