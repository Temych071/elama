<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Ads;

final class HasKeywordInTitleCheckRule extends YandexDirectAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'has-kws-in-title.title';
    protected string $desc = 'has-kws-in-title.desc';
    protected string $message = 'has-kws-in-title.message';

    public function check($object): bool
    {
        return $object->has_kws_in_title ?? false;
    }
}
