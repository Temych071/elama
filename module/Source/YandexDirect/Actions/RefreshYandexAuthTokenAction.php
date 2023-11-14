<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions;

use Exception;
use Illuminate\Support\Facades\Http;
use Module\Source\Sources\Models\SourceAuthToken;

final class RefreshYandexAuthTokenAction
{
    public function execute(SourceAuthToken $token): SourceAuthToken
    {
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $query = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $token->refresh_token,
            'client_id' => env('SOURCE_YANDEX_CLIENT_ID'),
            'client_secret' => env('SOURCE_YANDEX_CLIENT_SECRET'),
        ];

        $queryString = [];
        foreach ($query as $key => $val) {
            $queryString[] = "$key=$val";
        }
        $queryString = implode('&', $queryString);

        $url = 'https://oauth.yandex.ru/token';

        try {
            $res = Http::withHeaders($headers)
                ->withBody($queryString, 'application/x-www-form-urlencoded')
                ->post($url)
                ->throw()
                ->json();
        } catch (Exception) {
            $token->makeInvalid();
            return $token;
        }

        $token->refreshToken(
            token: $res['access_token'],
            refreshToken: $res['refresh_token'],
            expires_in: (int)$res['expires_in'],
        );

        return $token;
    }
}
