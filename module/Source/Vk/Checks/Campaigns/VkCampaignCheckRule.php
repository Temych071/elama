<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Campaigns;

use Module\Campaign\Checks\Checks\CampaignCheckRule;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Models\VkCampaignParam;

abstract class VkCampaignCheckRule extends CampaignCheckRule
{
    protected string $langPrefix = 'checks.' . Source::TYPE_VK . '.campaigns.';

    /**
     * @param  VkCampaignParam  $object
     * @return bool
     */
    protected function canApplyRule(\Module\Campaign\Checks\Contracts\CanBeChecked $object): bool
    {
        return (
            parent::canApplyRule($object)
            && $object instanceof VkCampaignParam
        );
    }
}
