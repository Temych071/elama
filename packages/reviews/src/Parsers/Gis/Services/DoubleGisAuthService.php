<?php

declare(strict_types=1);

namespace Reviews\Parsers\Gis\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Reviews\Parsers\Gis\DTO\DoubleGisOAuthData;
use Reviews\Parsers\Gis\Exceptions\DoubleGisAuthException;

final class DoubleGisAuthService
{
    public const SESSION_ID_CACHE_KEY = 'reviews.parsers.2gis.service_account.oauth-data';

    protected readonly string $login;
    protected readonly string $password;
    protected readonly string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.2gis.reviews_internal_api_url');
        $this->login = config('services.2gis.service_account.login');
        $this->password = config('services.2gis.service_account.password');
    }

    /**
     * @throws DoubleGisAuthException
     */
    public function getAccessToken(): string
    {
        $data = $this->getOAuthData();
        return "$data->token_type $data->access_token";
    }

    /**
     * @throws DoubleGisAuthException
     */
    public function makeAuthedRequest(): PendingRequest
    {
        return (new PendingRequest())
            ->withHeader('Authorization', $this->getAccessToken());
    }

    /**
     * @throws DoubleGisAuthException
     */
    private function getOAuthData(): DoubleGisOAuthData
    {
        $data = $this->authByCredentials();

        if ($data->created_at->addSeconds($data->expires_in)->isPast()) {
            $data = $this->refreshToken($data->refresh_token);
        }

        return $data;
    }

    public function resetSession(): void
    {
        Cache::forget(self::SESSION_ID_CACHE_KEY);
    }

    /**
     * @throws DoubleGisAuthException
     */
    private function refreshToken(string $refreshToken): DoubleGisOAuthData
    {
        try {
            $res = Http::post($this->apiUrl . 'users/refreshToken', [
                    'refreshToken' => $refreshToken,
                ])->throw()->json('result');
        } catch (RequestException $e) {
            throw new DoubleGisAuthException($e->getMessage());
        }

        $data = new DoubleGisOAuthData(
            token_type: $res['token_type'],
            access_token: $res['access_token'],
            refresh_token: $res['refresh_token'],
            expires_in: $res['expires_in'],
            created_at: now(),
        );

        Cache::put(self::SESSION_ID_CACHE_KEY, $data);

        return $data;
    }

    private function authByCredentials(): DoubleGisOAuthData
    {
        return Cache::remember(self::SESSION_ID_CACHE_KEY, null, function () {
            try {
                $res = Http::post($this->apiUrl.'users/auth', [
                        'login' => $this->login,
                        'password' => $this->password,
                    ])->throw()->json('result');
            } catch (RequestException $e) {
                throw new DoubleGisAuthException($e->getMessage());
            }

            return new DoubleGisOAuthData(
                token_type: $res['token_type'],
                access_token: $res['access_token'],
                refresh_token: $res['refresh_token'],
                expires_in: $res['expires_in'],
                created_at: now(),
            );
        });
    }
}
