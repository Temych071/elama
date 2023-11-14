<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexMetrika\Data\GoalData;
use Spatie\LaravelData\DataCollection;

/**
 * @property DataCollection|GoalData[] $goals
 * @property int $counter_id
 * @property int $id
 * @property bool $ecommerce
 */
final class MetrikaSourceSettings extends Model
{
    protected $table = 'source_metrika_settings';

    protected $fillable = [
        'counter_id',
        'name',
        'site',
        'permission',
        'status',
        'time_zone_name',
        'goals',
        'ecommerce',
    ];

    protected $casts = [
        'goals' => DataCollection::class . ':' . GoalData::class,
    ];

    public function source(): MorphOne
    {
        return $this->morphOne(Source::class, 'settings');
    }

    public function disableEcommerce(): void
    {
        $this->ecommerce = false;
        $this->save();
    }
}
