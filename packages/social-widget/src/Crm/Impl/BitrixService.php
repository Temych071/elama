<?php

declare(strict_types=1);


namespace SocialWidget\Crm\Impl;


use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use SocialWidget\Crm\CreateLeadCommand;
use SocialWidget\Crm\CrmRequestAuthException;
use SocialWidget\Crm\CrmRequestException;
use SocialWidget\Crm\CrmService;
use SocialWidget\Models\SocialWidget;

final class BitrixService implements CrmService
{

    public function __construct(
        private readonly ?SocialWidget $widget = null,
    ) {
    }

    /**
     * @throws RequestException
     */
    public function createLead(CreateLeadCommand $command): ?string
    {
        $queryURL = $this->widget->crm_integrations_settings->bx_webhook_url . '/crm.lead.add.json';

        $phones = array_map(static fn($item) => [
            'VALUE' => $item,
            'VALUE_TYPE' => 'MOBILE',
        ], $command->phones);

        $emails = array_map(static fn($item) => [
            'VALUE' => $item,
            'VALUE_TYPE' => 'WORK',
        ], $command->emails);

        try {
            $result = Http::throw()
                ->connectTimeout(2)
                ->contentType('application/json')
                ->timeout(4)
                ->post($queryURL, [
                    "fields" => array_filter([
                        "PHONE" => $phones,
                        "EMAIL" => $emails,
                        "TITLE" => $command->title,
//                        "NAME" => 'Unnamed',
//                        "UF_CRM_1575410143732" => true,    // пользовательское свойство
                        "UTM_SOURCE" => $command->utmSource,
                        "UTM_MEDIUM" => $command->utmMedium,
                        "UTM_CAMPAIGN" => $command->utmCampaign,
                        "STATUS_ID" => "NEW",
                        "OPENED" => "Y",
                        "SOURCE_ID" => "WEB",
                    ]),
                    'params' => ["REGISTER_SONET_EVENT" => "Y"]
                ])
                ->json();
        } catch (RequestException $exception) {
            $result = $exception->response->json();
            if (array_key_exists('error', $result)) {
                throw match ($result['error_description']) {
                    'Invalid request credentials' => new CrmRequestAuthException($result['error_description']),
                    default => new CrmRequestException($result['error_description']),
                };
            }
            throw $exception;
        }

        return (is_array($result) && !empty($result["result"])) ? (string)$result["result"] : null;
    }

    public function disableIntegration(): void
    {
        $settings = $this->widget->crm_integrations_settings;
        $settings->bx_enabled = false;
        $settings->bx_webhook_url = null;
        $this->widget->crm_integrations_settings = $settings;
        $this->widget->save();
    }
}
