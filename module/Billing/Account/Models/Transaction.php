<?php

declare(strict_types=1);

namespace Module\Billing\Account\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\Billing\Account\Enums\TransactionType;
use Module\Billing\Payments\Models\Payment;
use Module\Campaign\Models\Campaign;
use Module\User\Models\User;

/**
 * @property User $user
 * @property int $amount
 */
final class Transaction extends Model
{
    protected $table = 'account_transactions';

    protected $fillable = [
        'user_id',
        'campaign_id',
        'payment_id',
        'type',
        'amount',
        'formatted_amount',
        'description',
    ];

    protected $appends = [
        'formatted_amount',
    ];

    protected $casts = [
        'type' => TransactionType::class,
    ];

    public const AMOUNT_MULTIPLIER = 1000;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function formattedAmount(): Attribute
    {
        return Attribute::make(
            get: static fn ($value, $attrs): float => round($attrs['amount'] / self::AMOUNT_MULTIPLIER, 2),
            set: static fn (int|float $value): array => [
                'amount' => (int)($value * self::AMOUNT_MULTIPLIER)
            ],
        );
    }
}
