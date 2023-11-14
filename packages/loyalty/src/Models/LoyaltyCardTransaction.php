<?php

declare(strict_types=1);

namespace Loyalty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Loyalty\Enums\LoyaltyCardTransactionType;

final class LoyaltyCardTransaction extends Model
{
    protected $table = 'loyalty_transactions';

    public const MONEY_VALUES_MULTIPLIER = 100;

    protected $attributes = [
        'bonuses_amount' => 0,
        'bonuses_left' => null,
        'cheque_cost' => 0,
        'discount' => 0,
    ];

    protected $fillable = [
        'date',
        'type',
        'bonuses_amount',
        'bonuses_left',
        'cheque_cost',
        'discount',
        'discount_percent',
    ];

    protected $casts = [
        'type' => LoyaltyCardTransactionType::class,
        'date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(LoyaltyCard::class, 'loyalty_card_id');
    }
}
