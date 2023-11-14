<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Exceptions;

use Error;

final class CantGetItemFromPathBeginError extends Error
{
    public function __construct()
    {
        parent::__construct('Can`t get item from beginning of path. Use step() previously.');
    }
}
