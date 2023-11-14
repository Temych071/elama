<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Ads;

use Module\Source\Vk\Models\VkAdParam;

final class HasSavingAudienceCheckRule extends VkAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'has-saving-audience.title';
    protected string $desc = 'has-saving-audience.desc';
    protected string $message = 'has-saving-audience.message';

    /**
     * @param  VkAdParam  $object
     */
    public function check($object): bool
    {
        return !empty($object->events_retargeting_groups);
    }

    /**
     * @param  VkAdParam  $object
     */
    public function canApplyRule($object): bool
    {
        return in_array($object->ad_format, [9, 11]);
    }
}
