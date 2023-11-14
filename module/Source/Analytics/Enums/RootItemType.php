<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Enums;

enum RootItemType: string
{
    case AVITO = 'avito';
    case YANDEX_DIRECT = 'yandex-direct';
    case VK = 'vk';
    case VK_LEADS = 'vk-leads';
    case SEO_YANDEX_METRIKA = 'seo-yandex-metrika';
    case DIRECT_TRAFFIC_YANDEX_METRIKA = 'direct-traffic-yandex-metrika';
//    case SEO_GOOGLE_ANALYTICS = 'seo-google-analytics';
}
