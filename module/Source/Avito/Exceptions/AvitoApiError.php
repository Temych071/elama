<?php

declare(strict_types=1);

namespace Module\Source\Avito\Exceptions;

use Error;

class AvitoApiError extends Error
{
    public function __construct(int $code, string $message)
    {
        parent::__construct($message, $code);
    }
    public static function fromResponse(array $res): self
    {
        return new self($res['error']['code'] ?? -1, $res['error']['message'] ?? $res['error']);
    }
}
