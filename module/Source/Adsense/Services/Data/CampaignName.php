<?php

declare(strict_types=1);

namespace Module\Source\Adsense\Services\Data;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JetBrains\PhpStorm\ArrayShape;

final class CampaignName implements Arrayable, Jsonable
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {
    }

    /**
     * @return array{id: int, name: string}
     */
    #[ArrayShape(['id' => "int", 'name' => "string"])]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function toJson($options = 0): bool|string
    {
        return json_encode($this->toArray(), $options);
    }
}
