<?php

namespace Module\Source\Vk\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Module\Campaign\Checks\Contracts\AccountCanBeChecked;
use Module\Campaign\Checks\DTO\CheckObject;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Data\AccountData;
use Module\Source\Vk\Data\CampaignData;
use Module\Source\Vk\Data\ClientData;
use Module\Source\Vk\Data\WebhookData;
use Spatie\LaravelData\DataCollection;

/**
 * @property Collection|VkCampaignParam[] $vkCampaigns
 * @property Collection|VkCampaignParam[] $vkCampaignsSel
 * @property Collection|VkAdParam[] $vkAds
 * @property Collection|VkAdParam[] $vkAdsSel
 * @property Collection|VkAdsStatistics[] $vkAdsStatistics
 * @property AccountData $account
 * @property ClientData $client
 * @property Source $source
 * @property DataCollection|CampaignData[]|null $campaigns
 */
class VkSettings extends Model implements AccountCanBeChecked
{
    final public const ACCOUNT_STATUS_ACTIVE = 1;
    final public const ACCOUNT_STATUS_INACTIVE = 0;

    protected $table = 'source_vk_settings';

    protected $fillable = [
        'account',
        'client',
        'campaigns',
        'uuid',
        'webhooks',
        'is_vk_lead_messages',
        'is_vk_lead_forms',
    ];

    protected $casts = [
        'account' => AccountData::class,
        'client' => ClientData::class,
        'campaigns' => DataCollection::class . ':' . CampaignData::class,
        'webhooks' => DataCollection::class . ':' . WebhookData::class,
    ];

    public function vkCampaigns(): HasMany
    {
        return $this->hasMany(VkCampaignParam::class, 'setting_id');
    }

    public function getVkCampaignsSelAttribute(): Collection
    {
        $vkCampaigns = $this->vkCampaigns;
        if (is_null($this->campaigns)) {
            return $vkCampaigns;
        }

        $cIds = $this->campaigns
            ->toCollection()
            ->map(static fn (CampaignData $it): int => $it->id)
            ->toArray();

        return $vkCampaigns->whereIn('id', $cIds);
    }

    public function vkAds(): HasMany
    {
        return $this->hasMany(VkAdParam::class, 'setting_id');
    }

    public function getVkAdsSelAttribute(): Collection
    {
        $vkAds = $this->vkAds;
        if (is_null($this->campaigns)) {
            return $vkAds;
        }

        $cIds = $this->campaigns
            ->toCollection()
            ->map(static fn (CampaignData $it): int => $it->id)
            ->toArray();

        return $vkAds->whereIn('campaign_id', $cIds);
    }

    public function vkAdsStatistics(): HasMany
    {
        return $this->hasMany(VkAdsStatistics::class, 'settings_id')
            ->orderBy('day', 'desc');
    }

    public function source(): MorphOne
    {
        return $this->morphOne(Source::class, 'settings');
    }

    public function getAccountName(): string
    {
        return $this->client?->name ?? $this->account?->account_name ?? 'Unnamed';
    }

    public function getCheckObject(): CheckObject
    {
        $status = match ($this->account->account_status) {
            self::ACCOUNT_STATUS_ACTIVE => 'on',
            default => 'off',
        };

        if ($this->client instanceof \Module\Source\Vk\Data\ClientData) {
            return new CheckObject(
                type: 'account',
                name: $this->client->name,
                url: 'https://vk.com/ads?act=office&union_id=' . $this->client->id,
                index: $this->client->id,
                custom: [
                    'subtype' => 'agency',
                    'status' => $status,
                ],
            );
        }

        return new CheckObject(
            type: 'account',
            name: $this->account->account_name,
            url: 'https://vk.com/ads?act=office&union_id=' . $this->account->account_id,
            index: $this->account->account_id,
            custom: [
                'subtype' => 'personal',
                'status' => $status,
            ],
        );
    }
}
