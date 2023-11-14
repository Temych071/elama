<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Ads;

final class HasVCardCheckRule extends YandexDirectAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'has-v-card.title';
    protected string $desc = 'has-v-card.desc';
    protected string $message = 'has-v-card.message';

    public function check($object): bool
    {
        return !is_null($object->other['VCardId'] ?? null);
    }
}
