<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Account;

use Module\Source\YandexDirect\Models\YandexDirectSettings;

final class MoreCampaignsCheckRule extends YandexDirectAccountCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'more-campaigns.title';
    protected string $desc = 'more-campaigns.desc';
    protected string $message = 'more-campaigns.message';

    /**
     * @param  YandexDirectSettings  $object
     */
    protected function check($object): bool
    {
        return $object->directCampaigns->count() > 1;
    }
}
