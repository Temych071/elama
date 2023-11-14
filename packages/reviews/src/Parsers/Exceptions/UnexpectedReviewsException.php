<?php

declare(strict_types=1);

namespace Reviews\Parsers\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

final class UnexpectedReviewsException extends Exception
{
    #[Pure]
    public function __construct(?string $resBody = null)
    {
        if (empty($resBody)) {
            parent::__construct("Unexpected reviews API error.");
        } else {
            parent::__construct("Unexpected reviews API error. Response: $resBody");
        }
    }
}
