<?php

declare(strict_types=1);

namespace Module\Billing\Subscription\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Module\Billing\Subscription\Enums\PlanStatus;

/**
 * @property int $price
 * @property array $features
 * @property int $id
 * @property int $visits_limit
 * @property string $name
 */
final class Plan extends Model
{
    public const DAYS = 30;

    protected $table = 'billing_plans';

    protected $fillable = [
        'name',
        'description',
        'price',
        'visits_limit',
        'review_forms_limit',
        'status',
        'features',
        'formatted_price',
    ];

    protected $appends = [
        'formatted_price',
    ];

    protected $casts = [
//        'description' => 'json',
        'features' => 'json',
        'status' => PlanStatus::class,
    ];

    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(Subscription::class);
    }

    public function getPricePerDay(): int
    {
        return (int)round($this->price / self::DAYS);
    }

    public function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: static fn ($value, $attrs): float => round($attrs['price'] / 1000, 2),
            set: static fn (int|float $value): array => [
                'price' => (int)($value * 1000)
            ],
        );
    }
}
