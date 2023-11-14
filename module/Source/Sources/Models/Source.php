<?php

declare(strict_types=1);

namespace Module\Source\Sources\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Module\Billing\Subscription\Enums\SubscriptionStatus;
use Module\Campaign\Models\Campaign;
use Module\Source\GoogleAnalytics\Models\AnalyticsSettings;
use Module\Source\Sources\Enums\FetchingDataStatus;
use Module\Source\Vk\Models\VkSettings;
use Module\Source\YandexDirect\Models\YandexDirectSettings;
use Module\Source\YandexMetrika\Models\MetrikaSourceSettings;
use Module\User\Enums\UserRole;

/**
 * @property SourceAuthToken $authToken
 * @property int $settings_id
 * @property Campaign $campaign
 * @property YandexDirectSettings|MetrikaSourceSettings|VkSettings|AnalyticsSettings $settings
 * @property string $settings_type
 */
final class Source extends Model
{
    use SoftDeletes;

    public const TYPE_YANDEX_DIRECT = 'yandex-direct';
    public const TYPE_GOOGLE_ADS = 'google-ads';
    public const TYPE_VK = 'vk';
    public const TYPE_YANDEX_METRIKA = 'yandex-metrika';
    public const TYPE_GOOGLE_ANALYTICS = 'google-analytics';
    public const TYPE_FB = 'fb';
    public const TYPE_AVITO = 'avito';

    public const CABINET_SOURCES = [
        self::TYPE_YANDEX_DIRECT,
        self::TYPE_GOOGLE_ADS,
        self::TYPE_VK,
        self::TYPE_FB,
    ];

    public const METRICS_SOURCES = [
        self::TYPE_YANDEX_METRIKA,
        self::TYPE_GOOGLE_ANALYTICS,
//        self::TYPE_VK_LEAD,
    ];

    public const MIN_UPDATE_INTERVAL = 4;

    protected $casts = [
        'data_status' => FetchingDataStatus::class,
        'data_updated_at' => 'datetime',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function authToken(): BelongsTo
    {
        return $this->belongsTo(SourceAuthToken::class, 'source_oauth_token_id');
    }

    public function getIsTokenInvalidAttribute(): bool
    {
        return $this->authToken?->invalid ?? false;
    }

    public function settings(): MorphTo
    {
        return $this->morphTo();
    }

    public function fetchingStarted(string $batchId): Source
    {
        $this->data_status = FetchingDataStatus::fetching;
        $this->data_batch_id = $batchId;

        $this->data_updates_counter = $this->data_updated_at?->isCurrentDay() === true
            ? $this->data_updates_counter + 1
            : 1;

        $this->data_updated_at = Carbon::now();

        return $this;
    }

    public function fetchingSuccess(): Source
    {
        $this->data_status = FetchingDataStatus::updated;

        return $this;
    }

    public function fetchingError(): Source
    {
        $this->data_status = FetchingDataStatus::error;

        return $this;
    }

    public function isReady(): bool
    {
        if ($this->settings_type === self::TYPE_AVITO) {
            return true;
        }

        return !empty($this->settings_id);
    }

    public function scopeReady($q)
    {
        return $q->where(
            static fn ($q) => $q
                ->where('settings_type', self::TYPE_AVITO)
                ->orWhereNotNull('settings_id')
        );
    }

    public function shouldFetch(): bool
    {
        if (
            $this->campaign
                ->owner
                ->first()
                ?->role === UserRole::admin
        ) {
            return true;
        }

        return $this->campaign
            ->activeSubscription()
            ->where('status', SubscriptionStatus::active)
            ->exists();
    }
}
