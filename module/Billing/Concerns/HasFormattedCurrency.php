<?php

namespace Module\Billing\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasFormattedCurrency
{
    protected string $currencyAmountAttribute = 'amount';
    protected int $currencyAmountMultiplier = 1000;
    protected int $currencyAmountPrecision = 2;

    public function formattedAmount(): Attribute
    {
        return Attribute::make(
            get: static fn ($value, $attrs): float =>
                round(
                    $attrs[$this->currencyAmountAttribute] / $this->currencyAmountMultiplier,
                    $this->currencyAmountPrecision
                ),
            set: static fn (int|float $value): array => [
                $this->currencyAmountAttribute => (int)($value * $this->currencyAmountMultiplier)
            ],
        );
    }
}
