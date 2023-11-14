<?php

declare(strict_types=1);

namespace Module\Source\Analytics\DTO;

use Illuminate\Contracts\Support\Arrayable;
use JetBrains\PhpStorm\ArrayShape;

final class ItemData implements Arrayable
{
    public function __construct(
        private readonly string $name,
        private readonly string $index,
        private readonly MetricsData $metrics,
        private readonly array $path,
        private readonly bool $end = false,
        private readonly ?string $url = null,
    ) {
    }

    /**
     * @return array{name: string, index: string, metrics: mixed[], path: mixed[], end: bool}
     */
    #[ArrayShape([
        'name' => "string",
        'index' => "string",
        'metrics' => "array",
        'path' => "array",
        'end' => "bool",
        'url' => "string",
    ])]
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'index' => $this->index,
            'metrics' => $this->metrics->toArray(),
            'path' => $this->path,
            'end' => $this->end,
            'url' => $this->url,
        ];
    }

    public static function fromArray(array $data): self
    {
        if (empty($data['metrics'] ?? null)) {
            $data['metrics'] = [];
        }

        return new self(
            name: $data['name'],
            index: $data['index'],
            metrics: ($data['metrics'] instanceof MetricsData)
                ? $data['metrics']
                : MetricsData::fromArray($data['metrics']),
            path: $data['path'],
            end: $data['end'] ?? false,
            url: $data['url'] ?? null,
        );
    }
}
