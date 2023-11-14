<?php

namespace Module\Source\Avito\Enums;

enum ItemStatus: string
{
    case ACTIVE = 'active';
    case REMOVED = 'removed';
    case OLD = 'old';
    case BLOCKED = 'blocked';
    case REJECTED = 'rejected';
}
