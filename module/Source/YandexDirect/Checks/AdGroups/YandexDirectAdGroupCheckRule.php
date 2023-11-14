<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\AdGroups;

use Module\Campaign\Checks\Checks\AdGroupCheckRule;
use Module\Campaign\Checks\Contracts\CanBeChecked;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Models\DirectAdGroup;

abstract class YandexDirectAdGroupCheckRule extends AdGroupCheckRule
{
    protected string $langPrefix = 'checks.' . Source::TYPE_YANDEX_DIRECT . '.adgroups.';

    /**
     * @param  DirectAdGroup  $object
     * @return bool
     */
    protected function canApplyRule(CanBeChecked $object): bool
    {
        return (
            parent::canApplyRule($object)
            && $object instanceof DirectAdGroup
        );
    }
}
