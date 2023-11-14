<?php

declare(strict_types=1);

namespace Module\Billing\Payments\Models;

use App\Exceptions\BusinessException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Module\Billing\Account\Enums\TransactionType;
use Module\Billing\Account\Models\Transaction;
use Module\Billing\Account\Services\TransactionsService;
use Module\User\Models\User;

/**
 * @property int $user_id
 * @property Carbon $paid_at
 * @property User $user
 * @property string $amount
 * @property int $amountInt
 * @property string $invoice_uuid
 * @property int $id
 */
final class Payment extends Model
{
    protected $table = 'billing_payments';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    protected $fillable = [
        'amount',
        'paid_at',
        'invoice_uuid',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class, 'payment_id');
    }

    public function getAmountIntAttribute(): int
    {
        return (int)((float)$this->amount * 1000);
    }

    /**
     * @throws BusinessException
     */
    public function makeTransaction(TransactionType $type = TransactionType::REFILL_FROM_CARD): Transaction
    {
        return app(TransactionsService::class)
            ->debit($this->user, $this->amountInt, $type, $this);
    }
}
