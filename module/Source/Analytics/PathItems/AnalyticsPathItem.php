<?php

declare(strict_types=1);

namespace Module\Source\Analytics\PathItems;

use Module\Source\Analytics\Exceptions\EmptyPathItemException;
use Module\Source\Analytics\Services\AnalyticsPath;

class AnalyticsPathItem
{
    final public const STRING_SEPARATOR = ':';

    protected function __construct(
        protected readonly mixed $type,
        protected readonly ?string $arg = null,
    ) {
    }

    /**
     * @throws EmptyPathItemException
     */
    public static function make(string|AnalyticsPath $pathItem): static
    {
        if ($pathItem instanceof AnalyticsPath) {
            $pathItem = $pathItem->getCurrent();
        }

        if (empty($pathItem)) {
            throw new EmptyPathItemException();
        }
        $parsed = explode(static::STRING_SEPARATOR, $pathItem, 2);

        return new static($parsed[0], $parsed[1] ?? null);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getArg(): ?string
    {
        return $this->arg;
    }
}
