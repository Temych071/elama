<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Ads;

use Module\Campaign\Checks\Contracts\CanBeChecked;
use Module\Source\YandexDirect\Models\DirectAd;

final class HasImageCheckRule extends YandexDirectAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'has-image.title';
    protected string $desc = 'has-image.desc';
    protected string $message = 'has-image.message';

    /**
     * @param  DirectAd  $object
     */
    public function check($object): bool
    {
        return !is_null($object->other['AdImageHash'] ?? null);
    }

    protected function canApplyRule(CanBeChecked $object): bool
    {
        return isset($object->other['AdImageHash']);
    }
}
