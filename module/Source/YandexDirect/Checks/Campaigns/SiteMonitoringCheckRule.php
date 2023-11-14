<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Campaigns;

final class SiteMonitoringCheckRule extends YandexDirectCampaignCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'site-monitoring.title';
    protected string $desc = 'site-monitoring.desc';
    protected string $message = 'site-monitoring.message';

    public function check($object): bool
    {
        return $object->getSettings('ENABLE_SITE_MONITORING');
    }
}
