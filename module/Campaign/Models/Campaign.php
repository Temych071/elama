<?php

declare(strict_types=1);

namespace Module\Campaign\Models;

use Database\Factories\CampaignFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Loyalty\Models\Loyalty;
use Module\Billing\Subscription\Enums\PlanFeature;
use Module\Billing\Subscription\Enums\SubscriptionStatus;
use Module\Billing\Subscription\Models\Subscription;
use Module\Billing\Subscription\Services\SubscriptionService;
use Module\Campaign\DTO\ProjectSettingsChecks;
use Module\Campaign\Enums\ProjectMemberRole;
use Module\Campaign\Enums\ProjectPermission;
use Module\Campaign\Models\Concerns\BelongToUsers;
use Module\PlanFact\Models\PlanSettings;
use Module\Source\Analytics\Enums\CabinetItemType;
use Module\Source\Sources\Models\Source;
use Module\User\Enums\UserRole;
use Module\User\Models\User;
use Reviews\Models\AnswerTemplate;
use Reviews\Models\Review;
use Reviews\Models\ReviewForm;
use Reviews\Models\ReviewTag;
use SocialWidget\Models\SocialWidget;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * @property array $planfact_order
 * @property int $id
 * @property Subscription[]|Collection $subscriptions
 * @property ?Subscription $activeSubscription
 * @property Source[]|Collection $yandexDirectSources
 * @property Source[]|Collection $vkSources
 * @property Source[]|Collection $yandexMetrikaSources
 * */
final class Campaign extends Model
{
    use HasFactory;
    use BelongToUsers;
    use SoftDeletes;
    use HasRelationships;

    public const DEFAULT_PLANFACT_ORDER = [
        'expenses',
        'clicks',
        'cpc',
        'requests',
        'cr',
        'cpl',
        'income',
        'drr',
    ];

    public const ROLE_OWNER = ProjectMemberRole::OWNER;

    protected $fillable = ['name', 'notifications', 'analytics_parameters'];

    protected $attributes = [
        'settings_checks' => '{}',
    ];

    protected $casts = [
        'settings_checks' => ProjectSettingsChecks::class,
        'analytics_settings' => 'json',
        'planfact_settings' => 'json',
        'notifications' => 'array',
        'analytics_parameters' => 'array',
    ];

    protected static function booted(): void
    {
        self::deleted(static function (Campaign $campaign): void {
            $campaign->sources->each(fn (Source $source): ?bool => $source->delete());

            app(SubscriptionService::class)->end($campaign);
        });
    }

    public function sources(): HasMany
    {
        return $this->hasMany(Source::class)
            ->orderBy('settings_type');
    }

    public function source(string $sourceType): HasMany
    {
        return $this->hasMany(Source::class)
            ->where('settings_type', $sourceType);
    }

    public function metrikaSource(): HasOne
    {
        return $this->hasOne(Source::class)
            ->where('settings_type', Source::TYPE_YANDEX_METRIKA)
            ->limit(1);
    }

    public function googleAnalyticsSource(): HasOne
    {
        return $this->hasOne(Source::class)
            ->where('settings_type', Source::TYPE_GOOGLE_ANALYTICS)
            ->limit(1);
    }

    public function vkSource(): HasOne
    {
        return $this->hasOne(Source::class)
            ->where('settings_type', Source::TYPE_VK)
            ->limit(1);
    }

    public function googleAdsSource(): HasOne
    {
        return $this->hasOne(Source::class)
            ->where('settings_type', Source::TYPE_GOOGLE_ADS)
            ->limit(1);
    }

    public function statsSource(): HasOne
    {
        return $this->hasOne(Source::class)
            ->whereIn('settings_type', Source::METRICS_SOURCES)
            ->limit(1);
    }

    public function statsSources(): HasMany
    {
        return $this->hasMany(Source::class)
            ->whereIn('settings_type', Source::METRICS_SOURCES);
    }

    public function cabinetSources(): HasMany
    {
        return $this->hasMany(Source::class)
            ->whereIn('settings_type', Source::CABINET_SOURCES);
    }

    public function yandexDirectSource(): HasOne
    {
        return $this->hasOne(Source::class)
            ->where('settings_type', Source::TYPE_YANDEX_DIRECT)
            ->limit(1);
    }

    public function planSettings(): HasMany
    {
        return $this->hasMany(PlanSettings::class)
            ->orderBy('order');
    }

    public function getPlanfactOrderAttribute(): array
    {
        $order = $this->planfact_settings['order'] ?? null;
        if (is_null($order)) {
            $order = [];
            foreach (self::DEFAULT_PLANFACT_ORDER as $i => $field) {
                $order[] = [
                    'num' => $i + 1,
                    'field' => $field,
                ];
            }
        }
        return $order;
    }

    protected static function newFactory(): CampaignFactory
    {
        return CampaignFactory::new();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'campaign_id');
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'campaign_id')
            ->whereNot('status', SubscriptionStatus::ended);
    }

    public function yandexDirectSources(): HasMany
    {
        return $this->sources()
            ->where('settings_type', Source::TYPE_YANDEX_DIRECT);
    }

    public function vkSources(): HasMany
    {
        return $this->sources()
            ->where('settings_type', Source::TYPE_VK);
    }

    public function avitoSources(): HasMany
    {
        return $this->sources()
            ->where('settings_type', Source::TYPE_AVITO);
    }

    public function yandexMetrikaSources(): HasMany
    {
        return $this->sources()
            ->where('settings_type', Source::TYPE_YANDEX_METRIKA);
    }

    private ?array $analyticsCabinetOrder = null;

    /**
     * @return CabinetItemType[]
     */
    public function getAnalyticsCabinetOrder(): array
    {
        if (is_null($this->analyticsCabinetOrder)) {
            if (empty($this->analytics_settings['order'] ?? null)) {
                return $this->analyticsCabinetOrder = [
                    CabinetItemType::ACCOUNT,
                    CabinetItemType::CAMPAIGN,
                    CabinetItemType::AD_GROUP,
                    CabinetItemType::AD,
                ];
            }

            $this->analyticsCabinetOrder = [];
            foreach ($this->analytics_settings['order'] as $item) {
                if ($item['hidden'] || empty($item['type'])) {
                    continue;
                }

                $type = CabinetItemType::tryFrom($item['type']);
                if (!$type instanceof \Module\Source\Analytics\Enums\CabinetItemType) {
                    continue;
                }

                $this->analyticsCabinetOrder[] = $type;
            }
        }

        return $this->analyticsCabinetOrder;
    }

    public function getFeaturesAttribute(): array
    {
        return $this->getFeatures();
    }

    public function getFeatures(): array
    {
        return $this->activeSubscription?->plan?->features ?? [];
    }

    public function hasFeature(string|PlanFeature $feature): bool
    {
        if ($feature instanceof PlanFeature) {
            $feature = $feature->value;
        }

        return in_array($feature, $this->getFeatures(), true);
    }

    public function hasFeatures(array $features): bool
    {
        foreach ($features as $feature) {
            if (!$this->hasFeature($feature)) {
                return false;
            }
        }
        return true;
    }

    public function hasVkLeads(): bool
    {
        return !is_null(
            $this->vkSources->first(
                static fn (Source $source): bool => (
                    $source->settings?->is_vk_lead_messages
                    || $source->settings?->is_vk_lead_forms
                )
            )
        );
    }

    public function getMember(User $user): ?User
    {
        return $this->users
            ->first(static fn (User $member): bool => $member->id === $user->id);
    }

    /**
     * @param  ProjectPermission|ProjectPermission[]|null  $permissions
     */
    public function userHasAccess(User $user, ProjectPermission|array|null $permissions = null): bool
    {
        if ($user->role === UserRole::admin) {
            return true;
        }

        $member = $this->getMember($user);

        if (!$member instanceof \Module\User\Models\User) {
            return false;
        }

        if (empty($permissions)) {
            return true;
        }

        $rolePermissions = config('campaigns.permissions')[(string)($member->pivot->role)];

        if (empty($rolePermissions)) {
            return false;
        }

        foreach (Arr::wrap($permissions) as $expectingPermission) {
            if (
                Arr::first(
                    $rolePermissions,
                    static fn (ProjectPermission $item): bool => $item === $expectingPermission,
                ) === null
            ) {
                return false;
            }
        }

        return true;
//        return Arr::hasAny($rolePermissions, );
    }

    public function reviewForms(): HasMany
    {
        return $this->hasMany(ReviewForm::class, 'project_id');
    }

    public function getMaxReviewFormsCount(): int
    {
        return $this->activeSubscription?->plan?->review_forms_limit ?? 0;
    }

    public function canCreateReviewForm(): bool
    {
        if ($this->owner()->first()->role === UserRole::admin) {
            return true;
        }

        return $this->reviewForms()->count() < $this->getMaxReviewFormsCount();
    }

    public function socialWidgets(): HasMany
    {
        return $this->hasMany(SocialWidget::class, 'project_id');
    }

    public function loyalties(): HasMany
    {
        return $this->hasMany(Loyalty::class, 'project_id');
    }

    public function reviewTags(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->reviewForms(),
            (new ReviewForm())->reviews(),
            (new Review())->reviewTags(),
            (new ReviewTag())->tag(),
        );
    }

    public function reviewAnswerTemplates(): HasMany
    {
        return $this->hasMany(AnswerTemplate::class, 'project_id');
    }
}
