<?php

declare(strict_types=1);

namespace Module\LinkChecker\Models;

use App\Models\JobBatch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Module\LinkChecker\Dto\LinkCheckAdDto;
use Module\LinkChecker\Dto\LinkCheckItemDto;
use Module\Source\Sources\Models\Source;
use Spatie\LaravelData\DataCollection;

final class SeoAdsAudit extends Model
{
    public const STATUS_ERROR = 'error';
    public const STATUS_WAITING = 'waiting';
    public const STATUS_COMPLETED = 'completed';

    protected $table = 'seo_ads_audits';

    protected $fillable = [
        'source_id',
        'checks',
        'status',
        'batch_id',
    ];

    protected $casts = [
        'checks' => DataCollection::class . ':' . LinkCheckItemDto::class,
    ];

    /**
     * @param LinkCheckAdDto[] $checks
     */
    public static function startAudit(int|string $sourceId, iterable $checks): self
    {
        /** @var $model SeoAdsAudit */
        $model = self::query()->firstOrNew(['source_id' => $sourceId]);
        $model->status = self::STATUS_WAITING;
        $model->checks = $checks;
        $model->save();

        $model->seoAudits()->detach();

        return $model;
    }

    public function seoAudits(): BelongsToMany
    {
        return $this->belongsToMany(SeoAudit::class, 'seo_ads_audit_seo_audit');
    }

    public function jobBatch(): BelongsTo
    {
        return $this->belongsTo(JobBatch::class, 'batch_id');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }
}
