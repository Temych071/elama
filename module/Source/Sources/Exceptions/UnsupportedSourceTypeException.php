<?php

namespace Module\Source\Sources\Exceptions;

use Illuminate\Support\Arr;
use JetBrains\PhpStorm\Pure;
use Module\Source\Sources\Models\Source;
use RuntimeException;

class UnsupportedSourceTypeException extends RuntimeException
{
    #[Pure]
    public function __construct(string $type, string|array|null $expected = null)
    {
        if (!empty($expected)) {
            $expectedStr = implode('` or `', Arr::wrap($expected));
            parent::__construct("Unsupported source type. Type `$type`, expected `$expectedStr`.");
        } else {
            parent::__construct("Unsupported source type. Type `$type`.");
        }
    }

    /**
     * @throws UnsupportedSourceTypeException
     */
    public static function throwIfTypeNotIn(Source $source, string|array $types): void
    {
        if (!in_array($source->settings_type, Arr::wrap($types), true)) {
            throw new static($source->settings_type, $types);
        }
    }
}
