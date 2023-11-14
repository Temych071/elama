<?php

declare(strict_types=1);

namespace Reviews\Parsers\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

final class PlaceForbiddenException extends Exception
{
    #[Pure]
    public function __construct(string $placeId, ?string $resBody = null)
    {
        if (empty($resBody)) {
            parent::__construct("Place '$placeId' forbidden.");
        } else {
            parent::__construct("Place '$placeId' forbidden. Response: $resBody");
        }
    }
}
