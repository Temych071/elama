<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Module\Campaign\Checks\Contracts\AccountCanBeChecked;
use Module\Campaign\Checks\DTO\CheckObject;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Data\CampaignData;
use Spatie\LaravelData\DataCollection;

/**
 * @property null|DataCollection<int, CampaignData> $campaigns
 * @property Source $source
 * @property int $id
 *
 * @property Collection|DirectCampaign[] $directCampaigns
 * @property Collection|DirectAdGroup[] $directAdGroups
 * @property Collection|DirectAd[] $directAds
 *
 * @property Collection|DirectCampaign[] $directCampaignsSel
 * @property Collection|DirectAdGroup[] $directAdGroupsSel
 * @property Collection|DirectAd[] $directAdsSel
 */
final class YandexDirectSettings extends Model implements AccountCanBeChecked
{
    protected $table = 'source_yandex_direct_settings';

    protected $fillable = [
        'campaigns',
    ];

    protected $casts = [
        'campaigns' => DataCollection::class . ':' . CampaignData::class,
    ];

    public function source(): MorphOne
    {
        return $this->morphOne(Source::class, 'settings');
    }

    public function directCampaigns(): HasMany
    {
        return $this->hasMany(DirectCampaign::class, 'settings_id');
    }

    public function getDirectCampaignsSelAttribute(): Collection
    {
        return $this->directCampaigns->whereIn('campaign_id', $this->getCampaignIds());
    }

    public function directAdGroups(): HasMany
    {
        return $this->hasMany(DirectAdGroup::class, 'settings_id');
    }

    public function getDirectAdGroupsSelAttribute(): Collection
    {
        return $this->directAdGroups->whereIn('campaign_id', $this->getCampaignIds());
    }

    public function directAds(): HasMany
    {
        return $this->hasMany(DirectAd::class, 'settings_id');
    }

    public function getDirectAdsSelAttribute(): Collection
    {
        return $this->directAds->whereIn('campaign_id', $this->getCampaignIds());
    }

    public function getCampaigns(): DataCollection
    {
        return $this->campaigns ?? CampaignData::collection(
            $this->directCampaigns
                ->map(static fn (DirectCampaign $item): array => [
                    'Id' => $item->campaign_id,
                    'Name' => $item->name,
                ])
        );
    }

    public function getCampaignIds(): array
    {
        return $this->campaigns
                ?->toCollection()
                ?->map(static fn (CampaignData $i): int => $i->Id)
                ?->toArray()
            ?? $this->directCampaigns
                ->map(static fn (DirectCampaign $item): int => $item->campaign_id)
                ->toArray();
    }

    public function getCheckObject(): CheckObject
    {
        return new CheckObject(
            type: 'account',
            name: $this->source->authToken->nickname,
            url: "https://direct.yandex.ru/dna/grid/campaigns?ulogin=" . $this->source->authToken->nickname,
            index: $this->source->authToken->user_id,
        );
    }
}
