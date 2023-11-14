<?php

namespace Module\Source\Vk\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Module\Campaign\Checks\Contracts\AdCanBeChecked;
use Module\Campaign\Checks\DTO\CheckObject;

/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $day_limit
 * @property int $all_limit
 * @property int $approved
 * @property int $ad_format
 * @property bool $has_retargeting_groups_not
 * @property string $link_url
 * @property Collection|VkAdsStatistics[] $vkAdsStatistics
 */
final class VkAdParam extends Model implements AdCanBeChecked
{
    public const APPROVED_NOT_MODERATED = 0;
    public const APPROVED_WAIT = 1;
    public const APPROVED_SUCCESS = 2;
    public const APPROVED_REJECTED = 3;

    public const STATUS_STOPPED = 0;
    public const STATUS_STARTED = 1;
    public const STATUS_REMOVED = 2;

    protected $table = 'vk_ad_params';

    protected $fillable = [
        'uuid',
        'id',
        'campaign_id',
        'setting_id',
        'ad_format',
        'ad_platform',
        'ad_platform_no_wall',
        'ad_platform_no_ad_network',
        'all_limit',
        'day_limit',
        'approved',
        'autobidding_max_cost',
        'cost_type',
        'cpc',
        'cpm',
        'ocpm',
        'status',
        'title',
        'name',
        'description',
        'link_button',
        'link_domain',
        'link_url',
        'link_title',
        'events_retargeting_groups',
        'goal_type',
        'icon_src',
        'icon_src_2x',
        'image_src',
        'image_src_2x',
        'impressions_limit',
        'impressions_limited',
        'video',
        'weekly_schedule_hours',
        'weekly_schedule_use_holidays',
        'create_time',
        'update_time',
    ];

    public const UPDATED_AT = null;

    protected $casts = [
        'events_retargeting_groups' => 'array',
        'weekly_schedule_hours' => 'array',
        'has_retargeting_groups_not' => 'bool',
    ];

    public static function insertFillable(array $ads): int
    {
        $fillable = (new self())->getFillable();

        // из вк не всегда приходят все столбцы, а
        // bulk insert требует одинаковое кол-во столбцов во всех строках
        $ads = array_map(static function ($ad) use ($fillable): array {
            $res = [];

            foreach ($fillable as $colName) {
                $res[$colName] = $ad[$colName] ?? DB::raw('DEFAULT');
            }

            return $res;
        }, $ads);

        return self::insert($ads);
    }

    public function getCheckObject(): CheckObject
    {
        if ($this->approved === self::APPROVED_REJECTED) {
            $status = 'rejected';
        } else {
            $status = match ($this->status) {
                self::STATUS_STARTED => 'on',
                default => 'off'
            };
        }

        return new CheckObject(
            type: 'ad',
            name: $this->name,
            campaign: [
                'url' => "https://vk.com/ads?act=office&union_id=$this->campaign_id",
                'name' => $this->vkCampaign->name,
            ],
            url: "https://vk.com/ads?act=office&union_id=$this->id",
            index: $this->id,
            custom: ['status' => $status],
        );
    }

    public function vkCampaign(): BelongsTo
    {
        return $this->belongsTo(VkCampaignParam::class, 'campaign_id', 'id');
    }
}
