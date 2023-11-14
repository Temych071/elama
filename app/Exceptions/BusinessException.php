<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class BusinessException extends Exception
{
    #[Pure]
    public function __construct(private readonly string $userMessage, private readonly ?string $redirectTo = null)
    {
        parent::__construct("Business exception: $userMessage");
    }

    public function getUserMessage(): string
    {
        return $this->userMessage;
    }

    public function getRedirectTo(): ?string
    {
        return $this->redirectTo;
    }
}
