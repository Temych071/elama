<?php

declare(strict_types=1);

namespace Loyalty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Loyalty\Enums\LoyaltyClientGender;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

/**
 * @property array<string, string> $custom_fields
 */
final class LoyaltyForm extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $table = 'loyalty_forms';

    protected $fillable = [
        'loyalty_client_id',
        'loyalty_card_id',

        'name',
        'surname',
        'email',
        'birthday',
        'gender',

        'custom_fields',

        'email_notifications',
        'sms_notifications',
        'terms_accepted',
    ];

    protected $casts = [
        'gender' => LoyaltyClientGender::class,
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'terms_accepted' => 'boolean',
        'custom_fields' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'birthday' => 'datetime',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(LoyaltyCard::class, 'loyalty_card_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(LoyaltyClient::class, 'loyalty_client_id');
    }

    public function loyalty(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations(
            $this->card(),
            (new LoyaltyCard())->loyalty(),
        );
    }
}
