<?php

namespace Module\Source\YandexDirect\Checks\Ads;

class SitelinksHasUtmsCheckRule extends YandexDirectAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'sitelinks-utms.title';
    protected string $desc = 'sitelinks-utms.desc';
    protected string $message = 'sitelinks-utms.message';

    protected function check($object): bool
    {
        return $object->sitelink_utms < 1;
    }

    protected function canApplyRule(\Module\Campaign\Checks\Contracts\CanBeChecked $object): bool
    {
        return (
            parent::canApplyRule($object)
            && $object->sitelink_utms !== null
        );
    }
}
