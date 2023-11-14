<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Module\Campaign\Checks\Contracts\AdGroupCanBeChecked;
use Module\Campaign\Checks\DTO\CheckObject;

/**
 * @property int $ad_group_id
 * @property int $campaign_id
 * @property Collection|DirectAd[] $directAds
 * @property DirectCampaign $directCampaign
 * @property string $name
 * @property string $status
 * @property string $type
 * @property string $subtype
 * @property array $other
 * @property ?int $retarget_lists_num
 */
final class DirectAdGroup extends Model implements AdGroupCanBeChecked
{
    protected $table = 'yandex_direct_ad_groups';

    protected $fillable = [
        'ad_group_id',
        'campaign_id',

        'name',
        'status',
        'type',
        'subtype',

        'other',
    ];

    protected $casts = [
        'other' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function directAds(): HasMany
    {
        return $this->hasMany(DirectAd::class, 'ad_group_id', 'ad_group_id');
    }

    public function directCampaign(): BelongsTo
    {
        return $this->belongsTo(DirectCampaign::class, 'campaign_id', 'campaign_id');
    }

    public function getCheckObject(): CheckObject
    {
        return new CheckObject(
            type: 'adgroup',
            name: $this->name,
            url: "https://direct.yandex.ru/dna/groups-edit?campaigns-ids={$this->campaign_id}&groups-ids={$this->ad_group_id}",
            index: (string)$this->ad_group_id,
            custom: [
                'status' => match (true) {
                    !is_null($this->directAds->first(static fn (DirectAd $ad): bool => $ad->state === 'ON')) => 'on',
                    ($this->status === 'DRAFT') => 'draft',
                    default => 'off',
                },
            ],
        );
    }
}
