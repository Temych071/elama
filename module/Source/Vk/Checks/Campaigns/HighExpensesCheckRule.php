<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Campaigns;

use App\Infrastructure\DateRange;
use Illuminate\Support\Carbon;
use Module\Source\Vk\Models\VkAdsStatistics;
use Module\Source\Vk\Models\VkCampaignParam;

final class HighExpensesCheckRule extends VkCampaignCheckRule
{
    private const COMPARE_DAYS_NUM = 7;

    protected bool $textFromLang = true;
    protected string $title = 'high-expenses.title';
    protected string $desc = 'high-expenses.desc';
    protected string $message = 'high-expenses.message';

    private readonly DateRange $prePeriod;
    private readonly DateRange $curPeriod;

    public function __construct()
    {
        $this->curPeriod = DateRange::fromArray([
            Carbon::yesterday()->subDays(self::COMPARE_DAYS_NUM - 1),
            Carbon::yesterday(),
        ]);

        $this->prePeriod = DateRange::fromArray([
            Carbon::yesterday()->subDays(self::COMPARE_DAYS_NUM * 2),
            Carbon::yesterday()->subDays(self::COMPARE_DAYS_NUM),
        ]);

        $this->additionalLangParams['curPeriod'] = $this->curPeriod->formatByPreset('checks');
        $this->additionalLangParams['prePeriod'] = $this->prePeriod->formatByPreset('checks');
    }

    /**
     * @param  VkCampaignParam  $object
     */
    public function check($object): bool
    {
        // Последние 7 дней
        $firstExpenses = $this->getMaxExpensesByPeriod(
            $object,
            $this->curPeriod,
        );

        // 7 дней до последних 7 дней)
        $secondExpenses = $this->getMaxExpensesByPeriod(
            $object,
            $this->prePeriod,
        );

        if ($secondExpenses <= 0) {
            $secondExpenses = 1;
        }

        if ($firstExpenses < $secondExpenses) {
            return true;
        }

        return $this->calcDiffInPercents($firstExpenses, $secondExpenses) < 0.5;
    }

    private function getMaxExpensesByPeriod(VkCampaignParam $object, DateRange $period): float
    {
        $expensesList = collect();
        foreach ($period->getDaysWithFormat() as $day) {
            $expensesList->push($object->vkAdsStatistics[$day]->spent);
        }
        return $expensesList->max();
    }

    private function calcDiffInPercents(float|int $a, float|int $b): float|int
    {
        return abs(($a - $b) / (($a + $b) / 2));
    }

    /**
     * @param  VkCampaignParam  $object
     */
    public function canApplyRule($object): bool
    {
        if ($object->day_limit >= 0) {
            return false;
        }

        return (
            VkAdsStatistics::isStatsHasAllDaysInPeriod($object->vkAdsStatistics, $this->prePeriod)
            && VkAdsStatistics::isStatsHasAllDaysInPeriod($object->vkAdsStatistics, $this->curPeriod)
        );
    }
}
