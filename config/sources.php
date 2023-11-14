<?php

use Module\Source\Sources\Models\Source;

return [
    'list' => [
        Source::TYPE_YANDEX_DIRECT => [
            'type' => Source::TYPE_YANDEX_DIRECT,
            'name' => 'Яндекс.Директ',
            'driver' => 'source_yandex',
        ],
//        Source::TYPE_GOOGLE_ADS => [
//            'type' => Source::TYPE_GOOGLE_ADS,
//            'name' => 'Google ADS',
//            'driver' => 'source_google',
//        ],
        Source::TYPE_VK => [
            'type' => Source::TYPE_VK,
            'name' => 'ВКонтакте',
            'driver' => 'source_vk',
        ],
        Source::TYPE_YANDEX_METRIKA => [
            'type' => Source::TYPE_YANDEX_METRIKA,
            'name' => 'Яндекс.Метрика',
            'driver' => 'source_yandex',
        ],
        Source::TYPE_GOOGLE_ANALYTICS => [
            'type' => Source::TYPE_GOOGLE_ANALYTICS,
            'name' => 'Google Analytics',
            'driver' => 'source_google',
        ],
        Source::TYPE_AVITO => [
            'type' => Source::TYPE_AVITO,
            'name' => 'Авито',
            'driver' => 'source_avito',
//            'for_admin' => true,
        ],
//        Source::TYPE_FB => [
//            'type' => Source::TYPE_FB,
//            'name' => 'Facebook Ads',
//            'driver' => 'source_facebook',
//        ],
    ],
    'initial_range_days' => 62,
];
