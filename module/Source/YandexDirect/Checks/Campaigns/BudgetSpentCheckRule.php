<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Campaigns;

final class BudgetSpentCheckRule extends YandexDirectCampaignCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'budget-spent.title';
    protected string $desc = 'budget-spent.desc';
    protected string $message = 'budget-spent.message';

    public function check($object): bool
    {
        return !($object->state === 'OFF' && $object->status === 'ACCEPTED');
    }
}
