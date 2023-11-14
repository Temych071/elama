<?php

declare(strict_types=1);

namespace Module\Billing\Subscription\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Module\Billing\Subscription\Enums\SubscriptionStatus;
use Module\Campaign\Models\Campaign;
use Module\User\Models\User;

/**
 * @property Plan $plan
 * @property SubscriptionStatus $status
 * @property User $user
 * @property Campaign $campaign
 * @property Carbon $end_at
 * @property Carbon $last_billing_at
 */
final class Subscription extends Model
{
    protected $table = 'billing_subscriptions';

    protected $fillable = [
        'user_id',
        'campaign_id',
        'plan_id',
        'status',
        'start_at',
        'last_billing_at',
        'end_at',
    ];

    protected $casts = [
        'status' => SubscriptionStatus::class,
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'last_billing_at' => 'datetime',
    ];

    protected $appends = [
        'is_paused',
    ];

    protected $with = ['plan'];

    public function isPaused(): bool
    {
        return in_array($this->status, [SubscriptionStatus::noCharged, SubscriptionStatus::paused], true);
    }

    public function getIsPausedAttribute(): bool
    {
        return $this->isPaused();
    }

    public function isActive(): bool
    {
        return $this->status !== SubscriptionStatus::ended;
    }

    public function getDaysForCharge(): int
    {
        if (is_null($this->last_billing_at)) {
            return 1;
        }

        if ($this->isPaused()) {
            return 1;
        }

        if ($this->last_billing_at->isPast()) {
            return $this->last_billing_at->diffInDays();
        }

        return 0;
    }

    public function getChargeCost(): int
    {
        return $this->getDaysForCharge() * $this->plan->getPricePerDay();
    }

    public function charge(): bool
    {
        $this->last_billing_at = now();
        $this->status = SubscriptionStatus::active;

        return $this->save();
    }

    public function markAsEnded(): bool
    {
        $this->end_at = Carbon::now();
        $this->status = SubscriptionStatus::ended;

        return $this->save();
    }

    public function markAsNotCharge(): bool
    {
        $this->status = SubscriptionStatus::noCharged;

        return $this->save();
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
