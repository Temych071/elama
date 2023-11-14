<?php

declare(strict_types=1);

namespace Module\Source\Avito\Services;

use Illuminate\Http\Client\PendingRequest;
use Module\Source\Avito\Exceptions\AvitoApiError;
use Module\Source\Avito\Exceptions\AvitoApiInvalidAccessTokenError;
use Module\Source\Sources\Dto\AddAuthTokenDto;
use Module\Source\Sources\Models\SourceAuthToken;
use Throwable;

class BaseAvitoService
{
    protected readonly array $authScope;
    private readonly string $authUrl;
    private readonly string $apiUrl;
    protected string $client_id;
    protected string $client_secret;

    private bool $tokenExpired = false;

    public function __construct(
        private readonly ?SourceAuthToken $token = null,
    ) {
        $this->apiUrl = config('services.source_avito.api_url');
        $this->client_id = config('services.source_avito.client_id');
        $this->client_secret = config('services.source_avito.client_secret');
        $this->authUrl = config('services.source_avito.auth_url');
        $this->authScope = config('services.source_avito.auth_scope');
    }

    protected function getUserId(): int
    {
        return (int)$this->token->user_id;
    }

    public function getAuthUrl(): string
    {
        return $this->authUrl . self::buildQueryString([
                'response_type' => 'code',
                'client_id' => $this->client_id,
                'scope' => implode(',', $this->authScope),
            ]);
    }

    public function getUserInfoSelf(): array
    {
        return $this->get('core/v1/accounts/self');
    }

    protected function get(string $path, array $query = [], array $headers = []): array
    {
        return $this->send(__FUNCTION__, $path . self::buildQueryString($query), null, $headers);
    }

    protected function post(string $path, array|string|null $body = null, array $headers = []): array
    {
        return $this->send(__FUNCTION__, $path, $body, $headers);
    }

    private function send(string $method, string $path, array|string|null $body = null, array $headers = []): array
    {
        try {
            return $this->_send(...func_get_args());
        } catch (AvitoApiInvalidAccessTokenError) {
            $this->refreshUserToken();
            return $this->_send(...func_get_args());
        }
    }

    private function _send(string $method, string $path, array|string|null $body = null, array $headers = []): array
    {
        $request = new PendingRequest();

        if (is_array($body)) {
            $request->withBody(json_encode($body, JSON_THROW_ON_ERROR), 'application/json');
        } elseif (is_string($body)) {
            $request->withBody($body, 'application/x-www-form-urlencoded');
        }

        $request->withHeaders([
            ...$this->getAuthHeaders(),
            ...$headers,
        ]);

        $request->throw();
        $request->timeout(60);
        $request->connectTimeout(30);

        $url = rtrim($this->apiUrl, '/') . '/' . ltrim($path, '/');

        $res = $request->send($method, $url)->json();
        $this->throwIfError($res);

        return $res;
    }

    private function getAuthHeaders(): array
    {
        if (
            $this->token === null
            || !$this->token->exists
            || $this->tokenExpired
        ) {
            return [];
        }

        $this->refreshTokenIfExpired();

        return [
            'Authorization' => "Bearer {$this->token->token}",
        ];
    }

    private function refreshTokenIfExpired(): void
    {
        if (!$this->token->isExpired()) {
            return;
        }

        $this->tokenExpired = true;
        $this->refreshUserToken();
    }

    private function throwIfError(array $response): void
    {
        if (
            isset($response['error'])
            || isset($response['errors'])
        ) {
            throw AvitoApiError::fromResponse($response);
        }

        if (!($response['result']['status'] ?? true)) {
            throw match ($response['result']['message']) {
                'invalid access token' => new AvitoApiInvalidAccessTokenError("Invalid access token (source_oauth_tokens.id = {$this->token->id})"),
                default => new AvitoApiError(0, $response['result']['message'] ?? 'Undefined API error :('),
            };
        }
    }

    private function token(string $grant_type, array $params = []): array
    {
        return $this->_send('post', '/token', self::buildQueryString([
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => $grant_type,
            ...$params,
        ], false));
    }

    public function getUserToken(string $code): AddAuthTokenDto
    {
        $token = $this->token('authorization_code', [
            'code' => $code,
        ]);

        $user = $this->get('core/v1/accounts/self', [], [
            'Authorization' => "Bearer {$token['access_token']}",
        ]);

        return new AddAuthTokenDto(
            userId: (string)$user['id'],
            driver: 'source_avito',
            token: $token['access_token'],
            refreshToken: $token['refresh_token'],
            expiresIn: (int)$token['expires_in'],
            name: $user['name'],
            scopes: explode(',', (string) $token['scope']),
        );
    }

    private function refreshUserToken(): void
    {
        try {
            $res = $this->token('refresh_token', [
                'refresh_token' => $this->token->refresh_token,
            ]);

            $this->token->refreshToken(
                $res['access_token'],
                $res['refresh_token'],
                $res['expires_in'],
            );

            $this->tokenExpired = false;
        } catch (Throwable) {
            $this->token->makeInvalid();
            throw new AvitoApiInvalidAccessTokenError("Can't refresh access token (source_oauth_tokens.id = {$this->token->id})");
        }
    }

    protected static function buildQueryString(array $query = [], bool $withPrefix = true): string
    {
        if ($query === []) {
            return '';
        }

        $params = [];
        foreach ($query as $key => $value) {
            $params[] = "$key=$value";
        }

        return ($withPrefix ? '?' : '') . implode('&', $params);
    }
}
