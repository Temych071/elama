<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

class YandexDirectException extends Exception
{
    public const NOT_INITIALIZED_ERR_CODE = 513;
    public const INVALID_OAUTH_TOKEN_ERR_CODE = 53;

    /**
     * @throws YandexDirectNotInitializedException
     * @throws YandexDirectException
     * @throws YandexDirectInvalidOAuthTokenException
     */
    public static function throwIfError(array $response): void
    {
        if (!isset($response['error']) && !isset($response['error_code'])) {
            return;
        }

        if (isset($response['error_code'])) {
            $response = [
                'error' => [
                    'error_code' => $response['error_code'],
                    'error_string' => $response['error_str'],
                    'error_detail' => $response['error_detail'],
                ],
            ];
        }

        throw match ($response['error']['error_code']) {
            static::NOT_INITIALIZED_ERR_CODE => new YandexDirectNotInitializedException($response),
            static::INVALID_OAUTH_TOKEN_ERR_CODE => new YandexDirectInvalidOAuthTokenException($response),
            default => new static($response),
        };
    }

    #[Pure]
    public function __construct(
        public readonly array $response,
        ?Throwable $previous = null
    ) {
        if ($this->response['error']['error_detail'] !== '') {
            $message = $this->response['error']['error_detail'];
        } else {
            $message = $this->response['error']['error_string'];
        }

        parent::__construct(
            $message,
            $this->response['error']['error_code'],
            $previous
        );
    }
}
