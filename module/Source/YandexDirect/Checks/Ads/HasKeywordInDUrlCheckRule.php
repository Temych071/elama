<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Ads;

final class HasKeywordInDUrlCheckRule extends YandexDirectAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'has-kws-in-durl.title';
    protected string $desc = 'has-kws-in-durl.desc';
    protected string $message = 'has-kws-in-durl.message';

    public function check($object): bool
    {
        return $object->has_kws_in_durl ?? false;
    }
}
