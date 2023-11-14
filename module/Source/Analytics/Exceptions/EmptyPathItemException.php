<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Exceptions;

use App\Exceptions\BusinessException;

final class EmptyPathItemException extends BusinessException
{
    public function __construct()
    {
        parent::__construct("Path can`t contains empty items.");
    }
}
