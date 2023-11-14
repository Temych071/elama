<?php

declare(strict_types=1);

namespace Reviews\Parsers\Yandex\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

final class YandexAuthException extends Exception
{
    #[Pure]
    public function __construct()
    {
        parent::__construct('Can\'t get yandex session...');
    }
}
