<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Ads;

use Module\Source\Vk\Models\VkAdParam;

final class HasAllLimitCheckRule extends VkAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'has-all-limit.title';
    protected string $desc = 'has-all-limit.desc';
    protected string $message = 'has-all-limit.message';

    /**
     * @param  VkAdParam  $object
     */
    public function check($object): bool
    {
        return $object->all_limit > 0;
    }
}
