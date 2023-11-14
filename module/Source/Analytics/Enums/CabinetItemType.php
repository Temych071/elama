<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Enums;

enum CabinetItemType: string
{
    case ACCOUNT = 'account';
    case CAMPAIGN = 'campaign';
    case AD_GROUP = 'ad_group';
    case AD = 'ad';
}
