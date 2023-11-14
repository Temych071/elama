<?php

declare(strict_types=1);

namespace Module\Source\Avito\Enums;

enum ItemStatsField: string
{
    case VIEWS = 'uniqViews';
    case CONTACTS = 'uniqContacts';
    case FAVORITES = 'uniqFavorites';
}
