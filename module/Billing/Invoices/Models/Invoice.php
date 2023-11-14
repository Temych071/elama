<?php

declare(strict_types=1);

namespace Module\Billing\Invoices\Models;

use App\Exceptions\BusinessException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Module\Billing\Account\Enums\TransactionType;
use Module\Billing\Account\Models\Transaction;
use Module\Billing\Account\Services\TransactionsService;
use Module\Billing\Invoices\Notifications\NewInvoiceNotification;
use Module\Billing\Payments\Models\DiscountCode;
use Module\User\Models\User;
use Throwable;

/**
 * @property int $amount
 * @property User $user
 * @property ?DiscountCode $discountCode
 */
final class Invoice extends Model
{
//    use HasFormattedCurrency;

    public const TRANSACTION_TYPE = TransactionType::REFILL_BY_INVOICE;

    protected $table = 'billing_invoices';

    protected $fillable = [
        'user_id',
        'transaction_id',
        'phone',
        'email',
        'company_name',
        'amount',
        'formatted_amount',
        'discount_code_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_amount',
    ];

    protected static function boot(): void
    {
        parent::boot();

        self::created(static function (self $invoice) {
            if (!is_null($notifyTo = config('mail.new_invoice_notification_email'))) {
                Notification::route('mail', $notifyTo)
                    ->notify(new NewInvoiceNotification());
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function discountCode(): BelongsTo
    {
        return $this->belongsTo(DiscountCode::class, 'discount_code_id');
    }

    /**
     * @throws BusinessException|Throwable
     */
    public function confirm(?int $amount = null): self
    {
        $service = app(TransactionsService::class);

        if (is_null($amount)) {
            $amount = $this->amount;
        }

        DB::transaction(function () use ($service, $amount): void {
            $this->update(['amount' => $amount]);
            $transaction = $service->debit($this->user, $amount, self::TRANSACTION_TYPE);

            $this->transaction()
                ->associate($transaction)
                ->save();

            /** @noinspection PhpUnreachableStatementInspection */
            $this->discountCode
                ?->makeTransactions($this)
                ?->markAsUsed();
        });

        return $this;
    }

    public function formattedAmount(): Attribute
    {
        return Attribute::make(
            get: static fn ($value, $attrs): float => round($attrs['amount'] / 1000, 2),
            set: static fn (int|float $value): array => [
                'amount' => (int)($value * 1000)
            ],
        );
    }
}
