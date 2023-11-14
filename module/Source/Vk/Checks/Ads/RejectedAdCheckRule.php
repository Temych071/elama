<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Ads;

use Module\Source\Vk\Models\VkAdParam;

final class RejectedAdCheckRule extends VkAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'rejected-ad.title';
    protected string $desc = 'rejected-ad.desc';
    protected string $message = 'rejected-ad.message';

    /**
     * @param  VkAdParam  $object
     */
    public function check($object): bool
    {
        return $object->approved !== VkAdParam::APPROVED_REJECTED;
    }
}
