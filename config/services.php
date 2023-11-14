<?php

use Google\Service\Analytics;

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'api_key' => env('GOOGLE_API_KEY'),
        'redirect' => '/auth/google/callback',

        'service_accounts' => [
            'loyalty' => [ // Auth Config
                'type' => 'service_account',
                'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
                'token_uri' => 'https://oauth2.googleapis.com/token',
                'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',

                'project_id' => env('GOOGLE_SERVACC_LOYALTY_PROJECT_ID'),
                'private_key_id' => env('GOOGLE_SERVACC_LOYALTY_PKEY_ID'),
                'private_key' => env('GOOGLE_SERVACC_LOYALTY_PKEY'),
                'client_email' => env('GOOGLE_SERVACC_LOYALTY_CLIENT_EMAIL'),
                'client_id' => env('GOOGLE_SERVACC_LOYALTY_CLIENT_ID'),
                'client_x509_cert_url' => env('GOOGLE_SERVACC_LOYALTY_CLIENT_CERT_URL'),
            ],
        ],

        'pay' => [
            'issuer' => [
                'id' => env('GOOGLE_PAY_ISSUER_ID'),
            ],
        ],
    ],

    'elama' => [
        'response_type' => 'code',
        'client_id' => '61f2b657-a8b1-4693-923d-3056aec44d6a',
        'scope' => 'login',
        'state'=> 'drggetujkur',
        'redirect_uri' => '/auth/elama/callback',
    ],

    'apple' => [
        'wallet' => [
            'cert_path' => env('APPLE_WALLET_CERT_PATH'),
            'cert_pass' => env('APPLE_WALLET_CERT_PASS', ''),
            'team_identifier' => env('APPLE_WALLET_TEAM_IDENTIFIER', ''),
            'pass_type_identifier' => env('APPLE_WALLET_PASS_TYPE_IDENTIFIER', ''),
        ],
    ],

    // Sources

    'source_yandex' => [
        'client_id' => env('SOURCE_YANDEX_CLIENT_ID'),
        'client_secret' => env('SOURCE_YANDEX_CLIENT_SECRET'),
        'redirect' => '/source/add/callback',
    ],

    'source_vk' => [
        'client_id' => env('SOURCE_VK_CLIENT_ID'),
        'client_secret' => env('SOURCE_VK_CLIENT_SECRET'),
        'redirect' => '/source/add/vk/auth'
    ],

    'source_avito' => [
        'api_url' => 'https://api.avito.ru/',

        'client_id' => env('SOURCE_AVITO_CLIENT_ID'),
        'client_secret' => env('SOURCE_AVITO_CLIENT_SECRET'),

        'auth_url' => 'https://avito.ru/oauth/',
        'auth_scope' => [
            'short_term_rent:read',
            'stats:read',
            'items:info',
            'user:read',
            'user_balance:read',
            'user_operations:read',
        ],

        // Указывается при реге приложения
        'redirect' => '/source/add/avito/auth'
    ],

    'source_google_analytics' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => '/source/add/analytics/auth',

        'scopes' => [
            Analytics::ANALYTICS_READONLY,
        ],

        // Enables automatic token refresh.
        'approval_prompt' => 'force',
        'access_type' => 'offline',

        // Enables incremental scopes (useful if in the future we need access to another type of data).
        'include_granted_scopes' => true,
    ],

    'source_google_ads' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'dev_token' => env('ADSENSE_DEV_TOKEN'),
        'redirect' => '/source/add/ads/auth',

        'scopes' => [
            'https://www.googleapis.com/auth/adwords',
        ],

        // Enables automatic token refresh.
        'approval_prompt' => 'force',
        'access_type' => 'offline',

        // Enables incremental scopes (useful if in the future we need access to another type of data).
        'include_granted_scopes' => true,
    ],

    '2gis' => [
        'reviews_api_key' => env('DOUBLE_GIS_KEY', '37c04fe6-a560-4549-b459-02309cf643ad'),

        'reviews_internal_api_url' => 'https://api.account.2gis.com/api/1.0/',
        'service_account' => [
            'login' => env('DOUBLEGIS_SERVICE_ACCOUNT_LOGIN'),
            'password' => env('DOUBLEGIS_SERVICE_ACCOUNT_PASSWORD'),
        ],
    ],
    'yandex' => [
        'service_account' => [
            'login' => env('YANDEX_SERVICE_ACCOUNT_LOGIN'),
            'password' => env('YANDEX_SERVICE_ACCOUNT_PASSWORD'),
        ],
    ],

    'amocrm' => [
        'client_id' => env('AMOCRM_INTEGRATION_ID'),
        'client_secret' => env('AMOCRM_SECRET_KEY'),
        'redirect' => env('AMOCRM_REDIRECT_URL'),
    ],

    'prosto-sms' => [
        'api-key' => env('PROSTO_SMS_API_KEY'),
        'email' => env('PROSTO_SMS_EMAIL'),
        'password' => env('PROSTO_SMS_PASSWORD'),
        'sender' => env('PROSTO_SMS_SENDER'),
        'priority' => env('PROSTO_SMS_PRIORITY', 1),
    ],
];
