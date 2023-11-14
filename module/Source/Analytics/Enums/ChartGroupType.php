<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Enums;

enum ChartGroupType: string
{
    case DAYS_1 = 'days-1';
    case DAYS_3 = 'days-3';
    case DAYS_7 = 'days-7';
    case DAYS_30 = 'days-30';

    case WEEKS = 'weeks';
    case MONTHS = 'months';
}
