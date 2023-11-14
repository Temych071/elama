<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Services;

use Carbon\Carbon;
use Google\Client;
use Google\Service\Analytics;
use Google\Service\AnalyticsReporting;
use Module\Source\GoogleAnalytics\Services\Conversions\AnalyticsConversionReportService;
use Module\Source\GoogleAnalytics\Services\Conversions\AnalyticsConversionReportUAService;
use Module\Source\Sources\Dto\AddAuthTokenDto;
use Module\Source\Sources\Models\SourceAuthToken;

final class GoogleAnalyticsService
{
    protected Client $client;

    public function __construct()
    {
        $client = new Client();
        $client->setClientId(config('services.source_google_analytics.client_id'));
        $client->setClientSecret(config('services.source_google_analytics.client_secret'));
        $client->setRedirectUri(url(config('services.source_google_analytics.redirect')));
        $client->setScopes(config('services.source_google_analytics.scopes'));
        $client->setApprovalPrompt(config('services.source_google_analytics.approval_prompt'));
        $client->setAccessType(config('services.source_google_analytics.access_type'));
        $client->setIncludeGrantedScopes(config('services.source_google_analytics.include_granted_scopes'));
        $client->setPrompt('select_account consent');
        $this->client = $client;
    }

    public function analyticsService(): Analytics
    {
        return new Analytics($this->client);
    }

    public function getConversionReportService(string $viewId): AnalyticsConversionReportService
    {
        return new AnalyticsConversionReportUAService(
            viewId: $viewId,
            analytics: new AnalyticsReporting($this->client),
        );
    }

    public function getVisitsReportService(string $viewId): AnalyticsVisitsUAService
    {
        return new AnalyticsVisitsUAService(
            viewId: $viewId,
            analytics: new AnalyticsReporting($this->client),
        );
    }

    public function client(): Client
    {
        return $this->client;
    }

    public function connectUsingDto(AddAuthTokenDto $token): self
    {
        $this->client->setAccessToken([
            'access_token' => $token->getToken(),
            'refresh_token' => $token->getRefreshToken(),
            'expires_in' => $token->getExpiresIn(),
            'created' => now(),
        ]);

        return $this;
    }

    public function connectUsing(SourceAuthToken $token): GoogleAnalyticsService
    {
        $this->client->setAccessToken([
            'access_token' => $token->token,
            'refresh_token' => $token->refresh_token,
            'expires_in' => $token->expires_in,
            'created' => $token->token_created_at->getTimestamp(),
        ]);

        $this->refreshTokenIfExpired($token);

        return $this;
    }

    private function refreshTokenIfExpired(SourceAuthToken $token): void
    {
        if ($this->client->isAccessTokenExpired()) {
            $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
            $token->update([
                'token' => $this->client->getAccessToken()['access_token'],
                'refresh_token' => $this->client->getRefreshToken(),
                'expires_in' => $this->client->getAccessToken()['expires_in'],
                'token_created_at' => Carbon::now(),
            ]);
        }
    }
}
