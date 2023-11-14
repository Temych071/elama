<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Campaigns;

final class StoppedCampaignCheckRule extends YandexDirectCampaignCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'stopped-campaign.title';
    protected string $desc = 'stopped-campaign.desc';
    protected string $message = 'stopped-campaign.message';

    protected function check($object): bool
    {
        return $object->state !== 'SUSPENDED';
    }
}
