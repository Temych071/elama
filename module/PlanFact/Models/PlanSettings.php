<?php

declare(strict_types=1);

namespace Module\PlanFact\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Module\Campaign\Models\Campaign;
use Module\PlanFact\DTO\PlanFactGoalData;
use Module\PlanFact\DTO\PlanValues;
use Spatie\LaravelData\DataCollection;

/**
 * @property Carbon date_from
 * @property Carbon date_to
 * @property Collection|PlanValues[] values
 * @property Collection|PlanFactGoalData[] goals
 * @property ?array seo
 */
final class PlanSettings extends Model
{
    protected $table = 'plan_settings';

    public const METRIC_SUM_VALUES = [
        'expenses',
        'income',
        'requests',
        'clicks',
    ];

    public const METRIC_AVG_VALUES = [
        'cpl',
        'cpc',
        'cr',
        'drr',
    ];

    /** @var string[] $key */
    public const METRIC_VALUES = [
        ...self::METRIC_SUM_VALUES,
        ...self::METRIC_AVG_VALUES,
    ];

    public const FILTER_VALUES = [
        'sources',
        'utm_campaign',
        'utm_source',
        'utm_medium',
        'campaign_name',
        'device',
        'domain',
        'goals',
        'seo',
    ];

    protected $fillable = [
        'name',
        ...self::FILTER_VALUES,
        'values',
    ];

    protected $casts = [
        'seo' => 'json',
        'utm_campaign' => 'json',
        'utm_source' => 'json',
        'utm_medium' => 'json',
        'campaign_name' => 'json',
        'sources' => 'json',
        'goals' => DataCollection::class . ':' . PlanFactGoalData::class,
        'values' => DataCollection::class . ':' . PlanValues::class,
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getValues(Carbon $date): ?PlanValues
    {
        return $this->values->search(static fn ($item) => $item->getFrom()->isSameMonth($date));
    }
}
