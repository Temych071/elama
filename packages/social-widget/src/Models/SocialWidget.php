<?php

declare(strict_types=1);

namespace SocialWidget\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Module\Campaign\Models\Campaign;
use Ramsey\Uuid\Uuid;
use SocialWidget\DTO\AmoCrmAccessToken;
use SocialWidget\DTO\WidgetCrmIntegrationsSettings;
use SocialWidget\DTO\WidgetMessengersSettings;
use SocialWidget\DTO\WidgetStatsIntegrationsSettings;
use SocialWidget\DTO\WidgetViewSettings;

/**
 * @property string $id
 * @property string $name
 * @property int $project_id
 * @property Campaign $project
 * @property WidgetViewSettings $view_settings
 * @property WidgetMessengersSettings $messengers_settings
 * @property array<string, string> $messengers
 * @property string[] $messengers_list
 * @property WidgetStatsIntegrationsSettings $stats_integrations_settings
 * @property WidgetCrmIntegrationsSettings $crm_integrations_settings
 * @property ?AmoCrmAccessToken $amo_access_token
 * @property ?string $amo_domain
 */
final class SocialWidget extends Model
{
    use SoftDeletes;

    protected $table = 'sw_widgets';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'project_id',
        'name',
        'view_settings',
        'messengers_settings',

        'stats_integrations_settings',
        'crm_integrations_settings',
    ];

    protected $casts = [
        'view_settings' => WidgetViewSettings::class,
        'messengers_settings' => WidgetMessengersSettings::class,

        'stats_integrations_settings' => WidgetStatsIntegrationsSettings::class,
        'crm_integrations_settings' => WidgetCrmIntegrationsSettings::class,

        'amo_access_token' => AmoCrmAccessToken::class,
    ];

    protected $hidden = [
        'amo_access_token',
    ];

    protected $attributes = [
        'view_settings' => '{}',
        'messengers_settings' => '{}',
        'stats_integrations_settings' => '{}',
        'crm_integrations_settings' => '{}',
    ];

    protected $appends = [
        'amo_connected',
    ];

    protected static function boot():  void
    {
        parent::boot();

        self::creating(static function (Model $model): void {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'project_id');
    }

    public function stats(): HasMany
    {
        return $this->hasMany(SocialWidgetStats::class, 'widget_id');
    }

    public function loadMedia(?UploadedFile $file): ?string
    {
        if ($file === null) {
            return null;
        }

        return Storage::disk('public')->putFileAs(
            "projects/$this->project_id/social-widget",
            $file,
            Str::uuid() . '.' . $file->extension(),
        );
    }

    public function removeMedia(?string $path): void
    {
        if ($path !== null) {
            Storage::disk('public')->delete($path);
        }
    }

    public function getMessengersAttribute(): array
    {
        $res = [];

        $settings = $this->messengers_settings;

        if ($settings?->tg_enabled) {
            $res['tg'] = Str::contains($settings->tg_nickname, 't.me/')
                ? $settings->tg_nickname
                : "https://t.me/$settings->tg_nickname";
        }

        if ($settings?->wa_enabled) {
            $res['wa'] = "https://wa.me/$settings->wa_phone";
            if (!empty($settings->wa_message)) {
                $res['wa'] .= '?text=' . str_replace("\n", ' ', $settings->wa_message);
            }
        }

        return $res;
    }

    public function getMessengersListAttribute(): array
    {
        $res = [];

        $settings = $this->messengers_settings;

        if ($settings?->tg_enabled) {
            $res[] = 'tg';
        }

        if ($settings?->wa_enabled) {
            $res[] = 'wa';
        }

        return $res;
    }

    public function getStatsCountersAttribute(): array
    {
        $res = [];

        $settings = $this->stats_integrations_settings;

        if ($settings?->ym_enabled) {
            $res[] = [
                'type' => 'ym',
                'counter_id' => $settings?->ym_counter_id,
            ];
        }

        if ($settings?->ga_enabled) {
            $res[] = [
                'type' => 'ga',
                'counter_id' => $settings?->ga_counter_id,
            ];
        }

        return $res;
    }

    public function getAmoConnectedAttribute(): bool
    {
        return !empty($this->amo_access_token);
    }
}
