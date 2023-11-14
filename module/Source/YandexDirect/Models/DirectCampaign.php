<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Module\Campaign\Checks\Contracts\CampaignCanBeChecked;
use Module\Campaign\Checks\DTO\CheckObject;

/**
 * @property int $campaign_id
 * @property int $id
 * @property string $name
 * @property string[] $negative_keywords
 * @property string $state
 * @property ?string $daily_budget_mode
 * @property array $other
 * @property Collection&DirectAd[] $ads
 * @property string $status
 * @property int $daily_budget_amount
 * @property ?int $bid_modifiers_num
 */
final class DirectCampaign extends Model implements CampaignCanBeChecked
{
//    use SoftDeletes;

    protected $table = 'yandex_direct_campaigns';

    protected $fillable = [
        'campaign_id',
        'name',
        'type',
        'status',
        'state',
        'other',
    ];

    protected $casts = [
        'other' => 'json',
    ];

    public function getNegativeKeywordsAttribute(): array
    {
        return $this->other['NegativeKeywords']['Items'] ?? [];
    }

    public function getDailyBudgetModeAttribute(): ?string
    {
        return $this->other['DailyBudget']['Mode'] ?? null;
    }

    public function getDailyBudgetAmountAttribute(): int
    {
        return (int)($this->other['DailyBudget']['Amount'] ?? 0);
    }

    public function getCheckObject(): CheckObject
    {
        return new CheckObject(
            type: 'campaign',
            name: $this->name,
            url: "https://direct.yandex.ru/dna/campaigns-edit?campaigns-ids={$this->campaign_id}",
            index: (string)$this->campaign_id,
            custom: [
                'status' => match (true) {
                    ($this->state === 'ON') => 'on',
                    ($this->status === 'DRAFT') => 'draft',
                    default => 'off',
                },
            ],
        );
    }

    public function ads(): HasMany
    {
        return $this->hasMany(DirectAd::class, 'campaign_id', 'campaign_id');
    }

    public function directAdGroups(): HasMany
    {
        return $this->hasMany(DirectAdGroup::class, 'campaign_id', 'campaign_id');
    }

    public function getSettings(string $key): bool
    {
        $filtered = array_filter(
            $this->other['Settings'] ?? [],
            static fn ($item): bool => $item['Option'] === $key
        );

        if ((array) $filtered === []) {
            return false;
        }

        return $filtered[array_key_first($filtered)]['Value'] === "YES";
    }

    public function isBiddingStrategyActive(string $which): bool
    {
        return ($this->other['BiddingStrategy'][$which]['BiddingStrategyType'] ?? 'SERVING_OFF') !== 'SERVING_OFF';
    }
}
