<?php

declare(strict_types=1);

namespace Loyalty\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Loyalty\Contracts\WalletService;
use Loyalty\DTO\LoyaltyCardSettings;
use Loyalty\DTO\LoyaltyFormSettings;
use Loyalty\Services\Wallet\GoogleWalletService;
use Module\Campaign\Models\Campaign;
use Ramsey\Uuid\Uuid;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

/**
 * @property string $api_token
 * @property LoyaltyFormSettings $form_settings
 * @property string $id
 * @property int $project_id
 * @property Campaign $project
 * @property ?Carbon $google_class_updated_at
 * @property Collection<LoyaltyClient>|LoyaltyClient[] $clients
 * @property Collection<LoyaltyCard>|LoyaltyCard[] $cards
 */
final class Loyalty extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public const MEDIA_FORM_LOGO = 'form_logo';
    public const MEDIA_CARD_LOGO = 'card_logo';
    public const MEDIA_CARD_BANNER = 'card_banner';

    protected $table = 'loyalty';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $attributes = [
        'form_settings' => '{}',
        'card_settings' => '{}',
    ];

    protected $fillable = [
        'name',
        'api_token',

        'form_settings',
        'card_settings',
    ];

    protected $casts = [
        'form_settings' => LoyaltyFormSettings::class,
        'card_settings' => LoyaltyCardSettings::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'google_class_updated_at' => 'datetime',
    ];

    protected $appends = [
        'form_logo_url',
        'card_logo_url',
        'card_banner_url',
    ];

    protected $hidden = [
        'api_token',
    ];

    protected static function boot(): void
    {
        parent::boot();

        self::creating(static function (Model $model): void {
            $model->setAttribute($model->getKeyName(), (string) Uuid::uuid4());
        });

        self::deleted(static function (self $model): void {
            $model->removeMedia(self::MEDIA_FORM_LOGO);
            $model->removeMedia(self::MEDIA_CARD_LOGO);
            $model->removeMedia(self::MEDIA_CARD_BANNER);
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'project_id');
    }

    public function cards(): HasMany
    {
        return $this->hasMany(LoyaltyCard::class, 'loyalty_id');
    }

    public function forms(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->cards(),
            (new LoyaltyCard())->form(),
        );
    }

    public function clients(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->cards(),
            (new LoyaltyCard())->form(),
            (new LoyaltyForm())->client(),
        );
    }

    public function transactions(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->cards(),
            (new LoyaltyCard())->transactions(),
        );
    }

    public function loadOrRemoveMedia(string $media, File|UploadedFile|null $file, bool $delete = false): void
    {
        if ($delete) {
            $this->removeMedia($media);
        } elseif ($file !== null) {
            $this->loadMedia($media, $file);
        }
    }

    public function getMediaStorage(): FilesystemAdapter
    {
        return Storage::disk('public');
    }

    public function loadMedia(string $media, File|UploadedFile|null $file): void
    {
        if ($file === null) {
            return;
        }

        $pathKey = "{$media}_path";
        if (!empty($this->$pathKey)) {
            $this->removeMedia($media);
        }

        $this->$pathKey = $this->getMediaStorage()->putFileAs(
            "projects/$this->project_id/loyalty",
            $file,
            Str::uuid()->toString().'.'.$file->extension()
        );
    }

    public function removeMedia(string $media): void
    {
        $pathKey = "{$media}_path";

        if (empty($this->$pathKey) || !$this->getMediaStorage()->exists($this->$pathKey)) {
            return;
        }

        $this->getMediaStorage()->delete($this->$pathKey);
        $this->$pathKey = null;
    }

    protected function getFormLogoUrlAttribute(): ?string
    {
        if (empty($this->form_logo_path)) {
            return null;
        }

        return $this->getMediaStorage()->url($this->form_logo_path);
    }

    protected function getCardLogoUrlAttribute(): ?string
    {
        if (empty($this->card_logo_path)) {
            return null;
        }

        return $this->getMediaStorage()->url($this->card_logo_path);
    }

    protected function getCardBannerUrlAttribute(): ?string
    {
        if (empty($this->card_banner_path)) {
            return null;
        }

        return $this->getMediaStorage()->url($this->card_banner_path);
    }

    public static function genApiToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * @return WalletService[]
     */
    public function getWalletServices(): array
    {
        return [
            app(GoogleWalletService::class),
        ];
    }
}
