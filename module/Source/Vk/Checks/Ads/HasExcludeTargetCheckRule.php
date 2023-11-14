<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Ads;

use Module\Source\Vk\Models\VkAdParam;

final class HasExcludeTargetCheckRule extends VkAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'has-exclude-target.title';
    protected string $desc = 'has-exclude-target.desc';
    protected string $message = 'has-exclude-target.message';

    /**
     * @param  VkAdParam  $object
     */
    public function check($object): bool
    {
        return $object->has_retargeting_groups_not;
    }

    /**
     * @param  VkAdParam  $object
     */
    public function canApplyRule($object): bool
    {
        return !is_null($object->has_retargeting_groups_not);
    }
}
