<?php

declare(strict_types=1);

namespace Module\Source\Sources\Dto;

use JetBrains\PhpStorm\Pure;
use SocialiteProviders\Manager\OAuth2\User as SocialiteUser;

final class AddAuthTokenDto
{
    public function __construct(
        private readonly ?string $userId,
        private readonly string $driver,
        private readonly string $token,
        private readonly string $refreshToken,
        private readonly int $expiresIn,
        private readonly ?string $name,
        private readonly ?array $scopes = null,
    ) {
    }

    /**
     * @param SocialiteUser $user
     */
    #[Pure]
    public static function fromSocialite(string $driver, mixed $user, ?array $scopes = null): self
    {
        return new self(
            userId: (string) $user->id,
            driver: $driver,
            token: $user->token,
            refreshToken: $user->refreshToken ?? '',
            expiresIn: (int) $user->expiresIn,
            name: (string) ($user->nickname ?? $user->name ?? $user->email ?? 'unnamed'),
            scopes: $scopes,
        );
    }

    #[Pure]
    public static function fromGoogle(string $driver, array $accessToken): self
    {
        return new AddAuthTokenDto(
            userId: null,
            driver: $driver,
            token: $accessToken['access_token'],
            refreshToken: $accessToken['refresh_token'],
            expiresIn: (int)$accessToken['expires_in'],
            name: null,
            scopes: explode(' ', (string) $accessToken['scope']),
        );
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getScopes(): ?array
    {
        return $this->scopes;
    }
}
