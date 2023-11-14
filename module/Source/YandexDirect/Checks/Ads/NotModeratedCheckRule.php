<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Ads;

final class NotModeratedCheckRule extends YandexDirectAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'not-moderated.title';
    protected string $desc = 'not-moderated.desc';
    protected string $message = 'not-moderated.message';

    public function check($object): bool
    {
        return $object->status !== 'REJECTED';
    }
}
