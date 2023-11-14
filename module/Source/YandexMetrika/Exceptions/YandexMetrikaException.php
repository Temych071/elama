<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika\Exceptions;

use Exception;

class YandexMetrikaException extends Exception
{
    final public const INVALID_TOKEN_ERR_TYPE = 'invalid_token';

    private readonly array $errors;

    public static function throwIfError(array $response): void
    {
        if (isset($response['errors'])) {
            throw new static($response);
        }
    }

    public function throwIfNotExistType(string $type): void
    {
        foreach ($this->errors as $error) {
            if ($error['error_type'] === $type) {
                return;
            }
        }
        throw $this;
    }

    public function __construct(
        array $response,
    ) {
        $this->errors = $response['errors'];
        parent::__construct($response['message'], $response['code']);
    }
}
