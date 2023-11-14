<?php

declare(strict_types=1);

namespace Module\Source\Sources\Enums;

enum FetchingDataStatus: string
{
    case fetching = 'fetching';
    case error = 'error';
    case updated = 'updated';
}
