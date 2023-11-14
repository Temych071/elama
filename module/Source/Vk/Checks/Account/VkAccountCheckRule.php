<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Account;

use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Models\VkSettings;

abstract class VkAccountCheckRule extends \Module\Campaign\Checks\Checks\AccountCheckRule
{
    protected string $langPrefix = 'checks.' . Source::TYPE_VK . '.accounts.';

    /**
     * @param  VkSettings  $object
     * @return bool
     */
    protected function canApplyRule(\Module\Campaign\Checks\Contracts\CanBeChecked $object): bool
    {
        return (
            parent::canApplyRule($object)
            && $object instanceof VkSettings
        );
    }
}
