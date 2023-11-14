<?php

declare(strict_types=1);

namespace Module\DgMarks\DTO;

use Illuminate\Contracts\Support\Arrayable;
use JetBrains\PhpStorm\ArrayShape;

final class DgMarks implements Arrayable
{
    public function __construct(
        public readonly ?string $dg_source = null,
        public readonly ?string $dg_1 = null,
        public readonly ?string $dg_2 = null,
        public readonly ?string $dg_3 = null,
        public readonly ?string $dg_4 = null,
        public readonly ?string $dg_5 = null,
        public readonly ?string $dg_6 = null,
    ) {
    }

    /**
     * @return array{dg_source: string|null, dg_1: string|null, dg_2: string|null, dg_3: string|null, dg_4: string|null, dg_5: string|null, dg_6: string|null}
     */
    #[ArrayShape([
        'dg_source' => "null|string",
        'dg_1' => "null|string",
        'dg_2' => "null|string",
        'dg_3' => "null|string",
        'dg_4' => "null|string",
        'dg_5' => "null|string",
        'dg_6' => "null|string"
    ])]
    public function toArray(): array
    {
        return [
            'dg_source' => $this->dg_source,
            'dg_1' => $this->dg_1,
            'dg_2' => $this->dg_2,
            'dg_3' => $this->dg_3,
            'dg_4' => $this->dg_4,
            'dg_5' => $this->dg_5,
            'dg_6' => $this->dg_6,
        ];
    }

    public const PARTS = [
        'dg_source',
        'dg_1', 'dg_2',
        'dg_3', 'dg_4',
        'dg_5', 'dg_6',
    ];

    public static function fromArray(array $arr): self
    {
        if (array_is_list($arr)) {
            return self::fromList($arr);
        }

        return new self(
            $arr['dg_source'] ?? null,
            $arr['dg_1'] ?? null,
            $arr['dg_2'] ?? null,
            $arr['dg_3'] ?? null,
            $arr['dg_4'] ?? null,
            $arr['dg_5'] ?? null,
            $arr['dg_6'] ?? null,
        );
    }

    public static function fromList(array $lstArr): ?self
    {
        $assocArr = [];
        foreach ($lstArr as $index => $value) {
            if ($index >= count(self::PARTS)) {
                break;
            }

            $assocArr[self::PARTS[$index]] = $value;
        }

        return self::fromArray($assocArr);
    }
}
