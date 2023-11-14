<?php

declare(strict_types=1);

namespace Reviews\Models;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Module\Campaign\Models\Campaign;
use Reviews\DTO\ReviewSourceData;
use Reviews\DTO\StatsSourceData;
use Reviews\Enums\ReviewFormType;
use Reviews\Enums\ReviewSource;
use Reviews\Parsers\Gis\Services\DoubleGisReviewsService;
use Reviews\Parsers\Yandex\Services\YandexReviewsService;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * @property int $id
 * @property string $banner_path
 * @property string $thx_banner_path
 * @property string $logo_path
 * @property int $project_id
 * @property int $widget_yamaps
 * @property ?int $yandex_company_id
 * @property Carbon $external_fetched_at
 * @property Collection|Review[] $reviews
 */
final class ReviewForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasRelationships;

    public const DEFAULT_MEDIA_VALUE = 'default';

    protected $table = 'review_forms';

    protected $fillable = [
        'name',
        'slug',
        'page_settings',
        'phrases',
        'external_aggregators',
        'messengers',
        'min_stars_for_publish',
        'max_stars_for_notification',
        'banner_link',
        'thx_banner_link',
        'thx_button_link',
        'type',
        'widget_yamaps',
//        'widget_2gis',
    ];

    protected $hidden = [
        'logo_path',
        'banner_path',
        'max_stars_for_notification',
        'yandex_company_id',
    ];

    protected $casts = [
        'page_settings' => 'json',
        'phrases' => 'json',
        'external_aggregators' => 'json',
        'messengers' => 'json',
        'type' => ReviewFormType::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'widget_2gis_access' => 'boolean',
    ];

    protected $appends = [
        'logo_url',
        'banner_url',
        'thx_banner_url',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'project_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'review_form_id');
    }

    public function review(int $review_id): Model|HasOne
    {
        return $this->hasOne(Review::class)
            ->where('id', $review_id)
            ->firstOrFail();
    }

    public function stats(): HasMany
    {
        return $this->hasMany(ReviewFormStats::class, 'review_form_id');
    }

    /**
     * @return ReviewSourceData[]
     */
    public function getReviewSources(): array
    {
        $res = [];

        if (!empty($this->yandex_company_id)) {
            $res[] = new ReviewSourceData(
                ReviewSource::YANDEX,
                (string) $this->yandex_company_id,
                YandexReviewsService::class,
            );
        }

        if (!empty($this->widget_2gis)) {
            $res[] = new ReviewSourceData(
                ReviewSource::DOUBLE_GIS,
                (string) $this->widget_2gis,
                DoubleGisReviewsService::class,
            );
        }

        return $res;
    }

    /**
     * @return StatsSourceData[]
     */
    public function getStatsSources(): array
    {
        $res = [];

        if (!empty($this->yandex_company_id)) {
            $res[] = new StatsSourceData(
                ReviewSource::YANDEX,
                (string) $this->yandex_company_id,
                YandexReviewsService::class,
            );
        }

        return $res;
    }

    private function getMediaRelativePath(): string
    {
        return "projects/$this->project_id/reviews";
    }

    private function getMediaDisk(): Filesystem
    {
        return Storage::disk('public');
    }

    private function getMediaUrl(?string $media): ?string
    {
        if ($media === null) {
            return null;
        }

        if ($media === self::DEFAULT_MEDIA_VALUE) {
            return url('/images/reviews/default-logo.png');
        }

        return $this->getMediaDisk()
            ->url($media);
    }

    private function makeMediaName(): string
    {
        return Str::uuid()->toString();
    }

    public function tryToUploadMedia(string $key, File|UploadedFile|null $file): bool
    {
        if ($file === null) {
            return false;
        }
        $pathKey = "{$key}_path";

        $this->deleteMedia($key);

        $this->$pathKey = $this->getMediaDisk()->putFileAs(
            $this->getMediaRelativePath(),
            $file,
            $this->makeMediaName().'.'.$file->extension()
        );

        return true;
    }

    public function tryToUploadOrDeleteMedia(string $key, File|UploadedFile|null $file, bool $del = false): void
    {
        if ($del) {
            $this->deleteMedia($key);
        } else {
            $this->tryToUploadMedia($key, $file);
        }
    }

    public function copyMedia(string $key, ?string $path): bool
    {
        if (
            empty($path)
            || (
                $path !== self::DEFAULT_MEDIA_VALUE
                && !$this->getMediaDisk()->exists($path)
            )
        ) {
            return false;
        }

        $this->deleteMedia($key);

        $pathKey = "{$key}_path";

        if ($path !== self::DEFAULT_MEDIA_VALUE) {
            $newPath = $this->getMediaRelativePath().'/'.$this->makeMediaName().'.'.\Illuminate\Support\Facades\File::extension($path);

            if (!$this->getMediaDisk()->copy($path, $newPath)) {
                return false;
            }

            $this->$pathKey = $newPath;
        } else {
            $this->$pathKey = self::DEFAULT_MEDIA_VALUE;
        }

        return true;
    }

    public function deleteMedia(string $key): bool
    {
        $pathKey = "{$key}_path";

        if (
            !empty($this->$pathKey)
            && (
                $this->$pathKey === self::DEFAULT_MEDIA_VALUE
                || $this->getMediaDisk()->delete($this->$pathKey)
            )
        ) {
            $this->$pathKey = null;
            return true;
        }

        return false;
    }

    public function deleteAllMedia(): void
    {
        $this->deleteMedia('logo');
        $this->deleteMedia('banner');
        $this->deleteMedia('thx_banner');
    }

    /** @noinspection PhpUnused */
    public function getBannerUrlAttribute(): ?string
    {
        return $this->getMediaUrl($this->banner_path);
    }

    /** @noinspection PhpUnused */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->getMediaUrl($this->logo_path);
    }

    /** @noinspection PhpUnused */
    public function getThxBannerUrlAttribute(): ?string
    {
        return $this->getMediaUrl($this->thx_banner_path);
    }

    protected static function booted(): void
    {
        /** @noinspection PhpUnnecessaryStaticReferenceInspection */
        static::deleted(static function (self $reviewForm): void {
            $reviewForm->deleteAllMedia();
        });
    }

    public function tags(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->reviews(),
            (new Review())->reviewTags(),
            (new ReviewTag())->tag(),
        );
    }
}
