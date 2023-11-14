<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Campaigns;

use Module\Source\YandexDirect\Models\DirectCampaign;

final class HasBidModifiersCheckRule extends YandexDirectCampaignCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'has-bid-modifiers.title';
    protected string $desc = 'has-bid-modifiers.desc';
    protected string $message = 'has-bid-modifiers.message';

    /**
     * @param DirectCampaign $object
     */
    protected function check($object): bool
    {
        return ($object->bid_modifiers_num > 0);
    }

    /**
     * @param DirectCampaign $object
     */
    protected function canApplyRule(\Module\Campaign\Checks\Contracts\CanBeChecked $object): bool
    {
        return (
            parent::canApplyRule($object)
            && !is_null($object->bid_modifiers_num)
        );
    }
}
