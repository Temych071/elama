<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Campaigns;

final class NegativeKeywordsCheckRule extends YandexDirectCampaignCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'negative-keywords.title';
    protected string $desc = 'negative-keywords.desc';
    protected string $message = 'negative-keywords.message';

    protected function check($object): bool
    {
        return !is_null($object->negative_keywords);
    }
}
