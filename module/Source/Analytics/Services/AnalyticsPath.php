<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Services;

use ArrayAccess;
use ErrorException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;
use Module\Source\Analytics\Exceptions\CantGetItemFromPathBeginError;

final class AnalyticsPath implements Jsonable, Arrayable, ArrayAccess
{
    private const STRING_SEPARATOR = ',';

    protected int $pointer = -1;
    protected int $size = 0;

    /**
     * @param  string[]  $path
     */
    public function __construct(
        protected iterable $path = [],
    ) {
        foreach ($path as $ignored) {
            $this->size++;
        }
    }

    public static function make(string|iterable|null $value = null): self
    {
        if (empty($value)) {
            return new self();
        }

        if (is_string($value)) {
            return new self(explode(self::STRING_SEPARATOR, $value));
        }

        return new self($value);
    }

    /**
     * @param  string|string[]  $items
     * @return $this
     */
    public function add(string|array $items): self
    {
        foreach (Arr::wrap($items) as $item) {
            $this->path[] = $item;
            $this->size++;
        }
        return $this;
    }

    public function step(): ?string
    {
        if ($this->isEnd()) {
            return null;
        }

        $this->pointer++;
        return $this->path[$this->pointer];
    }

    public function getNext(?int $pointer = null): ?string
    {
        if ($this->isEnd($pointer)) {
            return null;
        }

        return $this->path[($pointer ?? $this->pointer) + 1];
    }

    public function getCurrent(?int $pointer = null): string
    {
        if ($this->isBegin($pointer)) {
            throw new CantGetItemFromPathBeginError();
        }

        return $this->path[($pointer ?? $this->pointer)];
    }

    public function reset(): void
    {
        $this->pointer = -1;
    }

    public function isBegin(?int $pointer = null): bool
    {
        return ($pointer ?? $this->pointer) < 0;
    }

    public function isEnd(?int $pointer = null): bool
    {
        return ($pointer ?? $this->pointer) >= ($this->size - 1);
    }

    public function isEmpty(): bool
    {
        return $this->size < 1;
    }

    public function toArray(): array
    {
        return (array)$this->path;
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->path, $options);
    }

    public function offsetExists(mixed $offset): bool
    {
        return (
            ((int)$offset) >= 0
            && ((int)$offset) < $this->size
        );
    }

    public function offsetGet(mixed $offset): ?string
    {
        return $this->getCurrent((int)$offset);
    }

    /**
     * @throws ErrorException
     */
    public function offsetSet(mixed $offset, mixed $value): never
    {
        throw new ErrorException("Can't modify analytics path's items.");
    }

    /**
     * @throws ErrorException
     */
    public function offsetUnset(mixed $offset): never
    {
        throw new ErrorException("Can't modify analytics path's items.");
    }
}
