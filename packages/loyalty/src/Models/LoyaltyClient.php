<?php

declare(strict_types=1);

namespace Loyalty\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

/**
 * @property array<string, string> $custom_fields
 * @property Carbon|mixed $phone_verified_at
 * @property Carbon|mixed $verify_code_gen_at
 * @property string $verify_code
 * @property Carbon|mixed $form_filled_at
 * @property string $loyalty_id
 * @property Collection<LoyaltyCard> $cards
 * @property string $phone
 */
final class LoyaltyClient extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    private const CODE_LIFETIME = 60;

    protected $table = 'loyalty_clients';

    protected $fillable = [
        'phone',
    ];

    protected $hidden = [
       'remember_token',
       'verify_code',
       'verify_code_gen_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'birthday' => 'datetime',
        'phone_verified_at' => 'datetime',
        'verify_code_gen_at' => 'datetime',
        'form_filled_at' => 'datetime',
    ];

    public function getAuthPassword(): string
    {
        return $this->verify_code ?? '';
    }

    public function forms(): HasMany
    {
        return $this->hasMany(LoyaltyForm::class, 'loyalty_client_id');
    }

    public function cards(): HasManyDeep
    {
        return $this->hasOneDeepFromRelations(
            $this->forms(),
            (new LoyaltyForm())->card(),
        );
    }

    public function loyalties(): HasManyDeep
    {
        return $this->hasOneDeepFromRelations(
            $this->forms(),
            (new LoyaltyForm())->card(),
            (new LoyaltyCard())->loyalty(),
        );
    }

    public function formForLoyalty(Loyalty|string $loyalty): HasMany
    {
        if ($loyalty instanceof Loyalty) {
            $loyalty = $loyalty->id;
        }

        return $this->forms()
            ->whereIn(
                'loyalty_card_id',
                $this->cardForLoyalty($loyalty)
                    ->select('loyalty_cards.id')
            );
    }

    public function cardForLoyalty(Loyalty|string $loyalty): HasManyDeep
    {
        if ($loyalty instanceof Loyalty) {
            $loyalty = $loyalty->id;
        }

        return $this->cards()
            ->where('loyalty_id', $loyalty);
    }

    public function transactionsForLoyalty(Loyalty|string $loyalty): HasManyDeep
    {
        if ($loyalty instanceof Loyalty) {
            $loyalty = $loyalty->id;
        }

        return $this->hasManyDeepFromRelations(
            $this->forms(),
            (new LoyaltyForm())->card(),
            (new LoyaltyCard())->transactions(),
        )->where('loyalty_cards.loyalty_id', $loyalty);

        // TODO: Проверить. Не уверен что работает как надо
    }

    public function verifyCode(string $code): bool
    {
        if (
            empty($this->verify_code_gen_at)
            || now()->subMinutes(self::CODE_LIFETIME)->isAfter($this->verify_code_gen_at)
        ) {
            return false;
        }

        if ($this->verify_code !== $code) {
            return false;
        }

        if (empty($this->phone_verified_at)) {
            $this->phone_verified_at = now();
        }

        $this->verify_code = null;
        $this->verify_code_gen_at = null;
        $this->save();

        return true;
    }
}
