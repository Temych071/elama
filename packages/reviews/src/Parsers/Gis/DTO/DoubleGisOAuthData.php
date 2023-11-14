<?php

declare(strict_types=1);

namespace Reviews\Parsers\Gis\DTO;

use Illuminate\Support\Carbon;

final class DoubleGisOAuthData
{
    public function __construct(
        public string $token_type,
        public string $access_token,
        public string $refresh_token,
        public int $expires_in,
        public Carbon $created_at,
    ) {
    }
}
