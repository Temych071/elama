<?php

namespace Reviews\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class ReviewSourceNotAllowedForAnswersException extends Exception
{
    #[Pure]
    public function __construct()
    {
        parent::__construct('Review answers allowed only for yandex');
    }
}
