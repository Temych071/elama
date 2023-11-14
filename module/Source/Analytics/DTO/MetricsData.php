<?php

declare(strict_types=1);

namespace Module\Source\Analytics\DTO;

use Illuminate\Contracts\Support\Arrayable;
use JetBrains\PhpStorm\ArrayShape;

final class MetricsData implements Arrayable
{
    public function __construct(
        private readonly ?int $requests = null,
        private readonly float|int|null $expenses = null,
        private readonly ?int $clicks = null,
        private readonly ?float $cpc = null,
        private readonly ?float $cr = null,
        private readonly ?float $cpl = null,
        private readonly ?int $purchases = null,
        private readonly float|int|null $income = null,
        private readonly ?float $drr = null,
        private readonly ?int $impressions = null,
        private readonly ?float $ctr = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            requests: (int)($data['requests'] ?? null),
            expenses: (float)($data['expenses'] ?? null),
            clicks: (int)($data['clicks'] ?? null),
            cpc: (float)($data['cpc'] ?? null),
            cr: (float)($data['cr'] ?? null),
            cpl: (float)($data['cpl'] ?? null),
            purchases: (int)($data['purchases'] ?? null),
            income: (float)($data['income'] ?? null),
            drr: (float)($data['drr'] ?? null),
            impressions: (int)($data['impressions'] ?? null),
            ctr: (float)($data['ctr'] ?? null),
        );
    }

    /**
     * @return array{requests: int|null, expenses: float|int|null, clicks: int|null, cpc: float|null, cr: float|null, cpl: float|null, purchases: int|null, income: float|int|null, drr: float|null}
     */
    #[ArrayShape([
        'requests' => "int|null",
        'expenses' => "float|null",
        'clicks' => "int|null",
        'cpc' => "float|null",
        'cr' => "float|null",
        'cpl' => "float|null",
        'purchases' => "int|null",
        'income' => "float|null",
        'drr' => "float|null",
        'impressions' => "int|null",
        'ctr' => "float|null",
    ])]
    public function toArray(): array
    {
        return [
            'requests' => $this->requests,
            'expenses' => $this->expenses,
            'clicks' => $this->clicks,
            'cpc' => $this->cpc,
            'cr' => $this->cr,
            'cpl' => $this->cpl,
            'purchases' => $this->purchases,
            'income' => $this->income,
            'drr' => $this->drr,
            'impressions' => $this->impressions,
            'ctr' => $this->ctr,
        ];
    }
}
