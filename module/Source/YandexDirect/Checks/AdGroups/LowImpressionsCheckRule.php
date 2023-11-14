<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\AdGroups;

use Module\Source\YandexDirect\Models\DirectAdGroup;

final class LowImpressionsCheckRule extends YandexDirectAdGroupCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'low-impressions.title';
    protected string $desc = 'low-impressions.desc';
    protected string $message = 'low-impressions.message';

    /**
     * @param  DirectAdGroup  $object
     */
    public function check($object): bool
    {
        return (
            empty($object?->impressionsDelta)
            || (
                $object->impressionsDelta?->delta < 0
                || $object->impressionsDelta?->deltaPercents < 0.3
            )
        );
    }
}
