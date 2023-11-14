<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Module\Campaign\Checks\Contracts\AdCanBeChecked;
use Module\Campaign\Checks\DTO\CheckObject;

/**
 * @property string $status
 * @property string $state
 * @property array $other
 * @property int $campaign_id
 * @property int $ad_id
 * @property int $ad_group_id
 * @property string $href
 * @property bool $has_kws_in_title
 * @property bool $has_kws_in_durl
 * @property DirectCampaign $campaign
 * @property int $sitelink_utms
 * @property int $sitelink_desc
 */
final class DirectAd extends Model implements AdCanBeChecked
{
    public const STATE_ON = 'ON';

    protected $table = 'yandex_direct_ads';

    protected $fillable = [
        'settings_id',
        'ad_id',
        'campaign_id',
        'ad_group_id',

        'type',
        'subtype',
        'state',
        'status',
        'href',
        'domain',

        'other',
    ];

    protected $casts = [
        'other' => 'json',
        'has_kws_in_title' => 'bool',
        'has_kws_in_durl' => 'bool',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(DirectCampaign::class, 'campaign_id', 'campaign_id');
    }

    public function getCheckObject(): CheckObject
    {
        return new CheckObject(
            type: 'ad',
            name: "[#$this->ad_id] $this->domain",
            campaign: [
                'url' => 'https://direct.yandex.ru/dna/banners-edit?'
                    . "&campaigns-ids=$this->campaign_id",
                'name' => $this->campaign->name,
            ],
            url: 'https://direct.yandex.ru/dna/banners-edit?'
            . "&campaigns-ids=$this->campaign_id"
            . "&banners-ids=$this->ad_id",
            index: (string)$this->ad_id,
            custom: [
                'status' => match (true) {
                    ($this->state === 'ON') => 'on',
                    ($this->status === 'DRAFT') => 'draft',
                    default => 'off',
                },
            ],
        );
    }

    public function getPureHref(): string
    {
        [$href, $paramsString] = explode('?', $this->href);
        $paramsString = explode('&', Str::beforeLast($paramsString, '#'));

        $params = [];
        foreach ($paramsString as $param) {
            $p = explode('=', $param);
            if (!Str::containsAll($p[1], ['{', '}'])) {
                $params[] = implode('=', $p);
            }
        }

        return $href . '?' . implode('&', $params);
    }
}
