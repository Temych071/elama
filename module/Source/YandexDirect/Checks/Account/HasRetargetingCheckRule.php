<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Account;

use Module\Source\YandexDirect\Models\YandexDirectSettings;

final class HasRetargetingCheckRule extends YandexDirectAccountCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'has-retargeting-campaign.title';
    protected string $desc = 'has-retargeting-campaign.desc';
    protected string $message = 'has-retargeting-campaign.message';

    /**
     * @param  YandexDirectSettings $object
     */
    public function check($object): bool
    {
        return !is_null($object->directAdGroupsSel->firstWhere('retarget_lists_num', '>', 0));
    }
}
