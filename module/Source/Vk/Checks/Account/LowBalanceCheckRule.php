<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Account;

use Module\Source\Vk\Actions\Account\GetVkBudgetAction;
use Module\Source\Vk\Models\VkAdsStatistics;
use Module\Source\Vk\Models\VkSettings;

// TODO: Проверить
final class LowBalanceCheckRule extends VkAccountCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'low-balance.title';
    protected string $desc = 'low-balance.desc';
    protected string $message = 'low-balance.message';

    /**
     * @param  VkSettings  $object
     */
    public function check($object): bool
    {
        $balance = app(GetVkBudgetAction::class)->execute($object->source);
        $spent = $this->getAvgExpensesForDay($object);

        return $balance > ($spent * 3);
    }

    private function getAvgExpensesForDay(VkSettings $object): float
    {
        return $object->vkAdsStatistics
                ->groupBy('day')
                ->map(static fn ($statsByDay) => $statsByDay->sum(static fn (VkAdsStatistics $stat): int => $stat->spent))
                ->avg() ?? 0;
    }

    /**
     * @param  VkSettings  $object
     */
    public function canApplyRule($object): bool
    {
        return $object->account->can_view_budget ?? false;
    }
}
