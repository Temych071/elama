<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Campaigns;

final class HasLimitsCheckRule extends YandexDirectCampaignCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'has-limits.title';
    protected string $desc = 'has-limits.desc';
    protected string $message = 'has-limits.message';

    protected function check($object): bool
    {
        return !is_null($object->daily_budget_mode);
    }
}
