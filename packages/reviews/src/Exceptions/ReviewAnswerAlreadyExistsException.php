<?php

namespace Reviews\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class ReviewAnswerAlreadyExistsException extends Exception
{
    #[Pure]
    public function __construct()
    {
        parent::__construct('Review answer already exists');
    }
}
