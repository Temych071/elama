<?php

declare(strict_types=1);

namespace SocialWidget\DTO;

use Spatie\LaravelData\Data;

final class AmoCrmAccessToken extends Data
{
    public function __construct(
        public readonly string $tokenType,
        public readonly int $expiresIn,
        public readonly string $accessToken,
        public readonly string $refreshToken,
    ) {
    }
}
