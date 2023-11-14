<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\AdGroups;

use Module\Source\YandexDirect\Models\DirectAdGroup;

final class MoreAdsCheckRule extends YandexDirectAdGroupCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'more-ads.title';
    protected string $desc = 'more-ads.desc';
    protected string $message = 'more-ads.message';

    /**
     * @param  DirectAdGroup  $object
     */
    public function check($object): bool
    {
        return $object->directAds->count() > 1;
    }
}
