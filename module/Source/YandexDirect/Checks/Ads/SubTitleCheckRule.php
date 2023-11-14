<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Ads;

final class SubTitleCheckRule extends YandexDirectAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'sub-title.title';
    protected string $desc = 'sub-title.desc';
    protected string $message = 'sub-title.message';

    public function check($object): bool
    {
        return !empty($object->other['Title2'] ?? null);
    }

    protected function canApplyRule(\Module\Campaign\Checks\Contracts\CanBeChecked $object): bool
    {
        return (
            parent::canApplyRule($object)
            && $object->campaign->isBiddingStrategyActive('Search')
        );
    }
}
