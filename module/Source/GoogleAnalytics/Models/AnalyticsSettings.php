<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Module\Source\GoogleAnalytics\Data\AnalyticsGoalData;
use Module\Source\Sources\Models\Source;
use Spatie\LaravelData\DataCollection;

/**
 * @property int $google_id
 */
final class AnalyticsSettings extends Model
{
    protected $table = 'source_google_analytics_settings';

    protected $fillable = [
        'google_id',
        'google_name',
        'property_id',
        'property_name',
        'property_internal_id',
        'site',
        'view_id',
        'view_name',
        'goals',
    ];

    protected $casts = [
        'goals' => DataCollection::class . ':' . AnalyticsGoalData::class,
    ];

    public function source(): MorphOne
    {
        return $this->morphOne(Source::class, 'settings');
    }
}
