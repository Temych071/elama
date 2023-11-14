<?php

return [
    'yoo-kassa' => [
        'id' => (int) env('YOOKASSA_MARKET_ID', 0),
        'secret' => (string) env('YOOKASSA_MARKET_SECRET', ''),
    ],
    'robokassa' => [
        'merchant-id' => (string) env('ROBOKASSA_MERCHANT_ID', ''),
        'password-1' => (string) env('ROBOKASSA_PASSWORD_1', ''),
        'password-2' => (string) env('ROBOKASSA_PASSWORD_2', ''),
        'text_mode' => (bool) env('ROBOKASSA_TEST', false),
    ],
];
