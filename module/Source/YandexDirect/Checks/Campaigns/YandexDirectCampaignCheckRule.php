<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Campaigns;

use Module\Campaign\Checks\Checks\CampaignCheckRule;
use Module\Campaign\Checks\Contracts\CanBeChecked;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Models\DirectCampaign;

/**
 * @method getResult(DirectCampaign $object);
 * @method check(DirectCampaign $object);
 * */
abstract class YandexDirectCampaignCheckRule extends CampaignCheckRule
{
    protected string $langPrefix = 'checks.' . Source::TYPE_YANDEX_DIRECT . '.campaigns.';

    /**
     * @param  DirectCampaign  $object
     * @return bool
     */
    protected function canApplyRule(CanBeChecked $object): bool
    {
        return (
            parent::canApplyRule($object)
            && $object instanceof DirectCampaign
        );
    }
}
