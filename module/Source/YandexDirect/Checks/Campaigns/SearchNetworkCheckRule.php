<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Campaigns;

final class SearchNetworkCheckRule extends YandexDirectCampaignCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'search-network.title';
    protected string $desc = 'search-network.desc';
    protected string $message = 'search-network.message';

    protected function check($object): bool
    {
        return $object->isBiddingStrategyActive('Search')
            xor $object->isBiddingStrategyActive('Network');
    }
}
