<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Ads;

use Module\Campaign\Checks\Checks\AdCheckRule;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Models\VkAdParam;

abstract class VkAdCheckRule extends AdCheckRule
{
    protected string $langPrefix = 'checks.' . Source::TYPE_VK . '.ads.';

    /**
     * @param  VkAdParam  $object
     * @return bool
     */
    protected function canApplyRule(\Module\Campaign\Checks\Contracts\CanBeChecked $object): bool
    {
        return (
            parent::canApplyRule($object)
            && $object instanceof VkAdParam
        );
    }
}
