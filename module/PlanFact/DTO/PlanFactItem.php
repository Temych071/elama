<?php

declare(strict_types=1);

namespace Module\PlanFact\DTO;

use App\Infrastructure\DateRange;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

final class PlanFactItem implements Arrayable
{
    protected DateRange $date;

    public function __construct(
        public int|float $plan,
        public int|float $fact,
        DateRange|Carbon|null $date = new DateRange(),
    ) {
        if (!($date instanceof DateRange)) {
            $date = DateRange::make($date);
        }
        $this->date = $date;
    }

    public static function from(array $data): self
    {
        return new self(
            plan: $data['plan'] ?? 0,
            fact: $data['fact'] ?? 0,
            date: $data['date'] ?? null,
        );
    }

    /**
     * @param  array[]|self[]|Collection  $data
     * @return Collection|self[]
     */
    public static function collect(array|Collection $data): Collection
    {
        $lst = collect();
        foreach ($data as $item) {
            if (!($item instanceof self)) {
                $item = self::from($item);
            }
            $lst->push($item);
        }
        return $lst;
    }

    public function round(int $p): self
    {
        $this->plan = round($this->plan, $p, PHP_ROUND_HALF_EVEN);
        $this->fact = round($this->fact, $p, PHP_ROUND_HALF_EVEN);

        return $this;
    }

    /**
     * @return array{plan: int|float, fact: int|float, date: mixed[]}
     */
    #[Pure]
    #[ArrayShape(['date' => "array|\Illuminate\Support\Carbon[]", 'plan' => "float|int", 'fact' => "float|int"])]
    public function toArray(): array
    {
        return [
            'plan' => $this->plan,
            'fact' => $this->fact,
            'date' => $this->date->toArray(),
        ];
    }
}
