<?php

declare(strict_types=1);

namespace Reviews\Parsers\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

final class PlaceNotFoundException extends Exception
{
    #[Pure]
    public function __construct(string $placeId, ?string $resBody = null)
    {
        if (empty($resBody)) {
            parent::__construct("Place '$placeId' not found.");
        } else {
            parent::__construct("Place '$placeId' not found. Response: $resBody");
        }
    }
}
