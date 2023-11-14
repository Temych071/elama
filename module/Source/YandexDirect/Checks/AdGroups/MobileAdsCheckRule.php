<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\AdGroups;

use Module\Source\YandexDirect\Models\DirectAd;
use Module\Source\YandexDirect\Models\DirectAdGroup;

final class MobileAdsCheckRule extends YandexDirectAdGroupCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'mobile-ads.title';
    protected string $desc = 'mobile-ads.desc';
    protected string $message = 'mobile-ads.message';

    /**
     * @param  DirectAdGroup  $object
     */
    public function check($object): bool
    {
        return !is_null(
            $object->directAds->first(static fn (DirectAd $directAd): bool => ($directAd->other['Mobile'] ?? 'NO') === 'YES')
        );
    }
}
