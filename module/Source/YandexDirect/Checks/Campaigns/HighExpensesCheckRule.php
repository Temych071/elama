<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Campaigns;

final class HighExpensesCheckRule extends YandexDirectCampaignCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'high-expenses.title';
    protected string $desc = 'high-expenses.desc';
    protected string $message = 'high-expenses.message';

    public function check($object): bool
    {
        return (
            empty($object?->expensesDelta)
            || (
                $object->expensesDelta?->delta > 0
                || $object->expensesDelta?->deltaPercents < 0.3
            )
        );
    }
}
