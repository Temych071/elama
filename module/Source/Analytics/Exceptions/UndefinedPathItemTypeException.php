<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Exceptions;

use App\Exceptions\BusinessException;

final class UndefinedPathItemTypeException extends BusinessException
{
    public function __construct(string $givenType)
    {
        parent::__construct("Undefined path item `$givenType`.");
    }
}
