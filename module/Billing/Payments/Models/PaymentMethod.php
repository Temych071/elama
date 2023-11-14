<?php

declare(strict_types=1);

namespace Module\Billing\Payments\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\Billing\Payments\Enums\PaymentMethodStatus;
use Module\Billing\Payments\Services\RoboKassaService;
use Module\User\Models\User;
use Throwable;

/**
 * @property User $user
 * @property int $id
 * @property string $name
 * @property string $method_id
 * @property PaymentMethodStatus $status
 */
final class PaymentMethod extends Model
{
    protected $table = 'billing_payment_methods';

    protected $fillable = [
        'user_id',
        'name',
        'method_id',
        'status',
    ];

    protected $casts = [
        'status' => PaymentMethodStatus::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pay(int $amount): self
    {
        try {
            app(RoboKassaService::class)
                ->payBySavedMethod($this->user, $this->method_id, $amount);
        } catch (Throwable) {
            $this->status = PaymentMethodStatus::UNAVAILABLE;
            $this->save();
        }

        return $this;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
