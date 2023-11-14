<?php

namespace Module\Source\Vk\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Module\Campaign\Checks\Contracts\CampaignCanBeChecked;
use Module\Campaign\Checks\DTO\CheckObject;

/**
 * @property string $name
 * @property int $id
 * @property int $status
 * @property int $day_limit
 * @property int $all_limit
 * @property Collection|VkAdParam[] $vkAds
 * @property Collection|VkAdsStatistics[] $vkAdsStatistics
 */
final class VkCampaignParam extends Model implements CampaignCanBeChecked
{
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    protected $table = 'vk_campaign_params';

    protected $fillable = [
        'uuid',
        'id',
        'setting_id',
        'type',
        'name',
        'status',
        'all_limit',
        'day_limit',
        'start_time',
        'stop_time',
        'create_time',
        'update_time',
    ];

    public static function updateAll(array $campaigns, int $settingId): int
    {
        $model = new self();

        $campaigns = array_map(static fn($campaign): array => array_intersect_key($campaign, array_flip($model->getFillable())), $campaigns);

        self::where('setting_id', $settingId)->delete();

        return self::insert($campaigns);
    }

    public function getCheckObject(): CheckObject
    {
        return new CheckObject(
            type: 'campaign',
            name: $this->name,
            url: "https://vk.com/ads?act=office&union_id=$this->id",
            index: $this->id,
            custom: [
                'status' => match ($this->status) {
                    self::STATUS_ACTIVE => 'on',
                    default => 'off'
                },
            ],
        );
    }

    public function vkAds(): HasMany
    {
        return $this->hasMany(VkAdParam::class, 'campaign_id');
    }

    public function vkAdsStatistics(): HasMany
    {
        return $this->hasMany(VkAdsStatistics::class, 'campaign_id')
            ->orderBy('day', 'desc');
    }
}
