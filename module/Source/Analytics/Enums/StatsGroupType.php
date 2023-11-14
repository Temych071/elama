<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Enums;

enum StatsGroupType: string
{
    case SUMMARY = 'summary';
//    case SOURCE_TYPE = 'source_type';
    case ACCOUNT = 'account';
    case CAMPAIGN = 'campaign';
    case AD_GROUP = 'ad_group';
    case AD = 'ad';
}
