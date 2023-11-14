<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Campaigns;

final class LinkMetricsServiceCheckRule extends YandexDirectCampaignCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'link-metrics.title';
    protected string $desc = 'link-metrics.desc';
    protected string $message = 'link-metrics.message';

    public function check($object): bool
    {
        return count($object->other['CounterIds']['Items'] ?? []) > 0;
    }
}
