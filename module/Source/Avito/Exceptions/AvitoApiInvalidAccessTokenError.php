<?php

declare(strict_types=1);

namespace Module\Source\Avito\Exceptions;

final class AvitoApiInvalidAccessTokenError extends AvitoApiError
{
    public function __construct(?string $overrideMessage = null)
    {
        parent::__construct(0, $overrideMessage ?? "Invalid access token");
    }
}
