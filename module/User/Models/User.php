<?php

declare(strict_types=1);

namespace Module\User\Models;

use App\Notifications\AfterUserRegistrationEmail;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Module\Billing\Account\Models\Transaction;
use Module\Billing\Invoices\Models\Invoice;
use Module\Billing\Payments\Models\Payment;
use Module\Billing\Payments\Models\PaymentMethod;
use Module\Billing\Subscription\Enums\SubscriptionStatus;
use Module\Billing\Subscription\Models\Subscription;
use Module\Campaign\Models\Campaign;
use Module\Campaign\Models\Concerns\HasCampaign;
use Module\Notification\Enums\NotificationSourceTypes;
use Module\Notification\Models\TelegramNotificationModel;
use Module\User\DTO\AutoRefillSettings;
use Module\User\Enums\UserRole;
use Module\User\Enums\UserTariff;

/**
 * @property UserTariff $tariff
 * @property AutoRefillSettings $auto_refill_settings
 * @property PaymentMethod[]|Collection $paymentMethods
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use HasCampaign;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'tariff',
        'notification_types',
        'notification_email',
        'last_visit_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => UserRole::class,
        'tariff' => UserTariff::class,
        'notification_types' => 'array',
        'last_visit_date' => 'datetime',

        'auto_refill_settings' => AutoRefillSettings::class,
    ];

    protected $attributes = [
        'auto_refill_settings' => '{}',
    ];

    protected static function booted(): void
    {
        self::deleted(static function (User $user): void {
            $user->ownCampaigns->each(fn (Campaign $campaign): ?bool => $campaign->delete());
        });
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function getTelegramChatId($notifiable)
    {
        return TelegramNotificationModel::whereUserId($this->id)->first()?->chat_id;
    }

    public function removeNotificationType(NotificationSourceTypes $notificationType): void
    {
        if (
            $this->notification_types === null
            || !in_array($notificationType->value, $this->notification_types, true)
        ) {
            return;
        }

        $notifications = $this->notification_types;

        $key = array_search($notificationType->value, $this->notification_types ?? [], true);
        if ($key !== false) {
            unset($notifications[$key]);
        }

        $this->update(['notification_types' => $notifications]);
    }

    public function updateNotificationTypes(NotificationSourceTypes $notificationType): void
    {
        $notifications = $this->notification_types ?? [];

        if (in_array($notificationType->value, $notifications, true)) {
            return;
        }

        $notifications[] = $notificationType->value;
        $this->update(['notification_types' => $notifications]);
    }

    public function hasEnabledNotificationType(NotificationSourceTypes $notificationType): bool
    {
        if ($this->notification_types === null) {
            return false;
        }
        return in_array($notificationType->value, $this->notification_types, true);
    }

    public function getMaxCampaignsCount(): int
    {
        return config('campaigns.limits.' . $this->tariff->value, 1);
    }

    public function settingsRequests(): HasMany
    {
        return $this->hasMany(SettingsRequest::class, 'user_id');
    }

    public function sendAfterRegistrationNotification(): void
    {
        $this->notify(new AfterUserRegistrationEmail());
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'user_id');
    }

    public function activeSubscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'user_id')
            ->where('status', SubscriptionStatus::active);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'user_id');
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class, 'user_id');
    }
}
