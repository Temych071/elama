<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Ads;

final class SitelinkSetCheckRule extends YandexDirectAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'site-link-set.title';
    protected string $desc = 'site-link-set.desc';
    protected string $message = 'site-link-set.message';

    public function check($object): bool
    {
        return !empty($object->other['SitelinkSetId'] ?? null);
    }
}
