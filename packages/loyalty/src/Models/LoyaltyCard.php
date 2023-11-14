<?php

declare(strict_types=1);

namespace Loyalty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

/**
 * @property LoyaltyClient $client
 * @property LoyaltyForm $form
 * @property string $card_number
 * @property ?Carbon $google_wallet_created_at
 * @property ?string $google_wallet_jwt_link
 */
final class LoyaltyCard extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $table = 'loyalty_cards';

    protected $fillable = [
        'loyalty_id',

        'card_number',
        'synced_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'synced_at' => 'datetime',
        'google_wallet_created_at' => 'datetime',
    ];

    protected $hidden = [
        'synced_at',
    ];

    protected $appends = [
        'is_active',
    ];

    public function loyalty(): BelongsTo
    {
        return $this->belongsTo(Loyalty::class, 'loyalty_id');
    }

    public function form(): HasOne
    {
        return $this->hasOne(LoyaltyForm::class, 'loyalty_card_id');
    }

    public function client(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations(
            $this->form(),
            (new LoyaltyForm())->client(),
        );
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(LoyaltyCardTransaction::class, 'loyalty_card_id');
    }

    public function balance(): int
    {
        return $this->transactions()
            ->whereNotNull('bonuses_left')
            ->latest('date')
            ->first()
            ?->bonuses_left ?? 0;
    }

    public function discount(): ?int
    {
        return $this->transactions()
            ->whereNotNull('discount_percent')
            ->latest('date')
            ->first()
            ?->discount_percent ?? null;
    }

    public function getBalanceAttribute(): int
    {
        return $this->balance();
    }

    public function getDiscountAttribute(): ?float
    {
        return $this->discount();
    }

    protected function getIsActiveAttribute(): bool
    {
        return !empty($this->synced_at);
    }
}
