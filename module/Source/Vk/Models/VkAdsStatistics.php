<?php

namespace Module\Source\Vk\Models;

use App\Exceptions\BusinessException;
use App\Infrastructure\DateRange;
use ArrayAccess;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $uniq_views_count
 * @property int $impressions
 * @property int $clicks
 * @property int $spent
 * @property int $reach
 * @property float $join_rate
 */
class VkAdsStatistics extends Model
{
    protected $table = 'vk_ads_statistics';

//    protected $fillable = [
//        'settings_id',
//        'ad_id',
//        'campaign_id',
//        'clicks',
//        'impressions',
//        'uniq_views_count',
//        'reach',
//        'spent',
//        'join_rate',
//        'day',
//    ];
    /**
     * @throws BusinessException
     */
    public function save(array $options = []): bool
    {
        throw new BusinessException('Vk statistics is read only');
    }

    /**
     * @throws BusinessException
     */
    public function update(array $attributes = [], array $options = []): bool
    {
        throw new BusinessException('Vk statistics is read only');
    }

    public function add(VkAdsStatistics $stat): static
    {
        $this->clicks += $stat->clicks;
        $this->impressions += $stat->impressions;
        $this->uniq_views_count += $stat->uniq_views_count;
        $this->reach += $stat->reach;
        $this->spent += $stat->spent;
        $this->join_rate = ($stat->join_rate + $this->join_rate) / 2;

        return $this;
    }

    /**
     * @param  ArrayAccess&static[]  $stats
     * @return $this
     */
    public function addArray(ArrayAccess $stats): static
    {
        /** @var static $stat */
        foreach ($stats as $stat) {
            $this->clicks += $stat->clicks;
            $this->impressions += $stat->impressions;
            $this->uniq_views_count += $stat->uniq_views_count;
            $this->reach += $stat->reach;
            $this->spent += $stat->spent;
            $this->join_rate += $stat->join_rate;
        }

        $this->join_rate /= (is_countable($stats) ? count($stats) : 0) + 1;

        return $this;
    }

    public static function groupByCampaign(Collection $stats, bool $dayKeys = false): \Illuminate\Support\Collection
    {
        return $stats
            ->groupBy('campaign_id')
            ->map(static function (Collection $statsByCampaign) use ($dayKeys): \Illuminate\Support\Collection {
                $res = $statsByCampaign
                    ->groupBy('day')
                    ->map(
                        static fn (Collection $statsByDay) => $statsByDay
                        ->first()
                        ->replicate()
                        ->addArray($statsByDay->slice(1))
                    );

                return $dayKeys ? $res : $res->values();
            });
    }

    public static function getStatsByPeriod(?Collection $stats, DateRange $period): Collection
    {
        return $stats?->only($period->getDaysWithFormat()) ?? new Collection([]);
    }

    public static function isStatsHasAllDaysInPeriod(?Collection $stats, DateRange $period): bool
    {
        if (is_null($stats)) {
            return false;
        }

        foreach ($period->getDaysWithFormat() as $day) {
            if (empty($stats[$day])) {
                return false;
            }
        }

        return true;
    }
}
