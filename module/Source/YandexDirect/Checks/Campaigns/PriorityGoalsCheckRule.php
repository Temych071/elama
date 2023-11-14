<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Campaigns;

use Module\Source\YandexDirect\Models\DirectCampaign;

final class PriorityGoalsCheckRule extends YandexDirectCampaignCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'priority-goals.title';
    protected string $desc = 'priority-goals.desc';
    protected string $message = 'priority-goals.message';

    /**
     * @param  DirectCampaign  $object
     */
    public function check($object): bool
    {
        return !is_null($object->other['PriorityGoals']);
    }

    /**
     * @param  DirectCampaign  $object
     */
    public function canApplyRule($object): bool
    {
        return isset($object->other['PriorityGoals']);
    }
}
