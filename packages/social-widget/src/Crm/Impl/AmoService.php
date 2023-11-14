<?php

declare(strict_types=1);

namespace SocialWidget\Crm\Impl;

use Error;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use JsonException;
use SocialWidget\Crm\CreateLeadCommand;
use SocialWidget\Crm\CrmRequestAuthException;
use SocialWidget\Crm\CrmService;
use SocialWidget\DTO\AmoCrmAccessToken;
use SocialWidget\Models\SocialWidget;

final class AmoService implements CrmService
{
    public function __construct(
        private readonly string $domain,
        private readonly ?SocialWidget $widget = null,
    ) {
    }

    public function createLead(CreateLeadCommand $command): ?string
    {
        $res = $this->sendAuth('POST', '/api/v4/leads/unsorted/forms', [
            [
                'source_uid' => $this->widget->id,
                'source_name' => $command->title,

                '_embedded' => [
                    'leads' => [
                        [
                            'name' => $command->title,
                            'custom_fields_values' => [
                                [
                                    'field_code' => 'UTM_SOURCE',
                                    'values' => [
                                        [
                                            'value' => $command->utmSource
                                        ]
                                    ]
                                ],
                                [
                                    'field_code' => 'UTM_MEDIUM',
                                    'values' => [
                                        [
                                            'value' => $command->utmMedium
                                        ]
                                    ]
                                ],
                                [
                                    'field_code' => 'UTM_CAMPAIGN',
                                    'values' => [
                                        [
                                            'value' => $command->utmCampaign
                                        ]
                                    ]
                                ],
                            ],
                        ]
                    ],
                    'contacts' => [[]],
                    'companies' => [[]],
                ],
                'metadata' => [
                    'category' => 'forms',
                    'form_id' => $this->widget->id,
                    'form_name' => 'DailyGrow - '.$this->widget->name,
                    'form_sent_at' => Carbon::now()->unix(),

                    'form_page' => $command->form_page,
                    'ip' => $command->ip,
                    'referer' => $command->referer,
                ],
            ],
        ]);

        return $res['_embedded']['unsorted'][0]['uid'] ?? null;
    }

    public function disableIntegration(): void
    {
        $settings = $this->widget->crm_integrations_settings;
        $settings->amo_enabled = false;
        $this->widget->crm_integrations_settings = $settings;
        $this->widget->amo_access_token = null;
        $this->widget->amo_domain = null;
        $this->widget->save();
    }

    public static function getAuthUrl(string $state = ''): string
    {
        return 'https://www.amocrm.ru/oauth?client_id='.config('services.amocrm.client_id').'&state='.$state.'&mode=post_message';
    }

    public function getAccessToken(string $code): AmoCrmAccessToken
    {
        $res = $this->send('POST', '/oauth2/access_token', [
            'client_id' => config('services.amocrm.client_id'),
            'client_secret' => config('services.amocrm.client_secret'),
            'redirect_uri' => config('services.amocrm.redirect'),
            'grant_type' => 'authorization_code',
            'code' => $code,
        ]);

        return new AmoCrmAccessToken(
            tokenType: $res['token_type'],
            expiresIn: $res['expires_in'],
            accessToken: $res['access_token'],
            refreshToken: $res['refresh_token'],
        );
    }

    public function refreshToken(): void
    {
        $res = $this->send('POST', '/oauth2/access_token', [
            'client_id' => config('services.amocrm.client_id'),
            'client_secret' => config('services.amocrm.client_secret'),
            'redirect_uri' => config('services.amocrm.redirect'),
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->widget->amo_access_token->refreshToken,
        ]);

        $this->widget->amo_access_token = new AmoCrmAccessToken(
            tokenType: $res['token_type'],
            expiresIn: $res['expires_in'],
            accessToken: $res['access_token'],
            refreshToken: $res['refresh_token'],
        );
        $this->widget->save();
    }

    /**
     * @throws JsonException
     */
    private function sendAuth(string $method, string $path, array|string $body = null, array $headers = []): array
    {
        if ($this->widget === null) {
            throw new Error("This method requires auth.");
        }

        $authHeaders = [
            ...$headers,
            'Authorization' => $this->widget->amo_access_token->tokenType.' '.$this->widget->amo_access_token->accessToken,
        ];

        try {
            return $this->send($method, $path, $body, $authHeaders);
        } catch (Exception) {
            try {
                $this->refreshToken();
                return $this->send($method, $path, $body, $authHeaders);
            } catch (Exception $e) {
//                $this->disableIntegration();
                throw new CrmRequestAuthException();
            }
        }
    }

    private function send(string $method, string $path, array|string $body = null, array $headers = []): array
    {
        $request = new PendingRequest();

        $request->withHeaders($headers);
//        $request->throw();
        $request->timeout(60);
        $request->connectTimeout(30);

        if (is_array($body)) {
            $request->withBody(json_encode($body, JSON_THROW_ON_ERROR), 'application/json');
        } elseif (is_string($body)) {
            $request->withBody($body, 'application/x-www-form-urlencoded');
        }

        $url = 'https://'.$this->domain.'/'.ltrim($path, '/');

        return $request->send($method, $url)->json();
    }
}
