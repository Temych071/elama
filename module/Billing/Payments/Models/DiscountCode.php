<?php

declare(strict_types=1);

namespace Module\Billing\Payments\Models;

use App\Exceptions\BusinessException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Module\Billing\Account\Enums\TransactionType;
use Module\Billing\Account\Services\TransactionsService;
use Module\Billing\Invoices\Models\Invoice;

/**
 * @property string $code
 * @property ?int $amount
 * @property ?int $percent
 * @property Carbon $used_at
 * @property int $id
 */
final class DiscountCode extends Model
{
    protected $table = 'billing_discount_codes';

    protected $fillable = [
        'code',
        'amount',
        'percent',
        'is_one_time',
        'is_active',
    ];

    public function markAsUsed(): self
    {
        $this->used_at = Carbon::now();
        $this->save();

        return $this;
    }

    /**
     * @throws BusinessException
     */
    public function makeTransactions(Payment|Invoice $initiator): self
    {
        $service = app(TransactionsService::class);

        if (!empty($this->amount)) {
            $service->debit(
                $initiator->user,
                $this->amount,
                TransactionType::DISCOUNT_CODE_AMOUNT,
                null,
                $this->code,
            );
        }

        if (!empty($this->percent)) {
            $paymentAmount = ($initiator instanceof Payment)
                ? $initiator->amount * 1000
                : $initiator->amount;

            $service->debit(
                $initiator->user,
                (int)(($paymentAmount * $this->percent) / 100),
                TransactionType::DISCOUNT_CODE_PERCENT,
                null,
                $this->code,
            );
        }

        return $this;
    }

    public function scopeAvailable(Builder $q): Builder
    {
        return $q->whereRaw('(NOT is_one_time OR used_at IS NULL)');
    }
}
