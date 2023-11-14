<?php

namespace Module\PlanFact\DTO;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class PlanFactData extends Data
{
    private const ROUNDED_VALUES = [
        'expenses' => 0,
        'income' => 0,
        'clicks' => 0,
        'requests' => 0,
        'cr' => 2,
        'cpc' => 2,
        'cpl' => 2,
        'drr' => 2,
    ];

    /**
     * @param  Collection|PlanFactItem[]  $linear
     */
    public function __construct(
        public string $title,
        public PlanFactItem $summary,
        public Collection|array $linear,
        public string $units = '',
        public bool $reverse = false,
    ) {
        if (isset(self::ROUNDED_VALUES[$this->title])) {
            $this->round(self::ROUNDED_VALUES[$this->title]);
        }
    }

    public function round(int $p): self
    {
        $this->summary->round($p);
        foreach ($this->linear as $item) {
            $item->round($p);
        }

        return $this;
    }
}
