<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Ads;

final class BannerCheckRule extends YandexDirectAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'network-banner.title';
    protected string $desc = 'network-banner.desc';
    protected string $message = 'network-banner.message';

    public function check($object): bool
    {
        return !empty($object->other['AdImageHash'] ?? null);
    }

    protected function canApplyRule(\Module\Campaign\Checks\Contracts\CanBeChecked $object): bool
    {
        return (
            parent::canApplyRule($object)
            && $object->campaign->isBiddingStrategyActive('Network')
        );
    }
}
