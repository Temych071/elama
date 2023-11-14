<?php

declare(strict_types=1);

namespace Module\Billing\Subscription\Enums;

enum PlanFeature: string
{
    case ANALYTICS = 'analytics';

    case PLANFACT = 'planfact';
    case PLANFACT_MORE_CABINETS = 'planfact-more-cabinets';

    // Фид
    case SMART_NOTIFICATIONS = 'smart-notifications';

    // Проверки
    case AUDIT = 'audit';
    case AUDIT_LINKS = 'audit-links';

    // Поддержка
    case SUPPORT_TG = 'support-tg';
    case SUPPORT_SETTINGS = 'support-settings';
    case SUPPORT_PRIORITY = 'support-priority';
}
