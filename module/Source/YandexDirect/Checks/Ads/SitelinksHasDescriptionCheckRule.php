<?php

namespace Module\Source\YandexDirect\Checks\Ads;

class SitelinksHasDescriptionCheckRule extends YandexDirectAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'sitelinks-desc.title';
    protected string $desc = 'sitelinks-desc.desc';
    protected string $message = 'sitelinks-desc.message';

    protected function check($object): bool
    {
        return $object->sitelink_desc < 1;
    }

    protected function canApplyRule(\Module\Campaign\Checks\Contracts\CanBeChecked $object): bool
    {
        return (
            parent::canApplyRule($object)
            && $object->sitelink_desc !== null
        );
    }
}
