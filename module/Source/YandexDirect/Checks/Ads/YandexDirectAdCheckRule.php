<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Ads;

use Module\Campaign\Checks\Checks\AdCheckRule;
use Module\Campaign\Checks\Contracts\CanBeChecked;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Models\DirectAd;

/**
 * @method getResult(DirectAd $object);
 * @method check(DirectAd $object): bool;
 * */
abstract class YandexDirectAdCheckRule extends AdCheckRule
{
    protected string $langPrefix = 'checks.' . Source::TYPE_YANDEX_DIRECT . '.ads.';
    /**
     * @param  DirectAd  $object
     * @return bool
     */
    protected function canApplyRule(CanBeChecked $object): bool
    {
        return (
            parent::canApplyRule($object)
            && $object instanceof DirectAd
        );
    }
}
