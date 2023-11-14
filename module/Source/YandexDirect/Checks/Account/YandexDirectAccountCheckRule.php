<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Account;

use Module\Campaign\Checks\Checks\AccountCheckRule;
use Module\Campaign\Checks\Contracts\CanBeChecked;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Models\YandexDirectSettings;

abstract class YandexDirectAccountCheckRule extends AccountCheckRule
{
    protected string $langPrefix = 'checks.' . Source::TYPE_YANDEX_DIRECT . '.accounts.';

    /**
     * @param  YandexDirectSettings  $object
     * @return bool
     */
    protected function canApplyRule(CanBeChecked $object): bool
    {
        return (
            parent::canApplyRule($object)
            && $object instanceof YandexDirectSettings
        );
    }
}
