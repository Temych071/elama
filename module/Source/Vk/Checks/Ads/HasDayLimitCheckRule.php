<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Ads;

use Module\Source\Vk\Models\VkAdParam;

final class HasDayLimitCheckRule extends VkAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'has-day-limit.title';
    protected string $desc = 'has-day-limit.desc';
    protected string $message = 'has-day-limit.message';

    /**
     * @param  VkAdParam  $object
     */
    public function check($object): bool
    {
        return $object->day_limit > 0;
    }
}
