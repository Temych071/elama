<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Ads;

final class HasExtensionsCheckRule extends YandexDirectAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'has-extensions.title';
    protected string $desc = 'has-extensions.desc';
    protected string $message = 'has-extensions.message';

    public function check($object): bool
    {
        return !empty($object->other['AdExtensions'] ?? null);
    }
}
