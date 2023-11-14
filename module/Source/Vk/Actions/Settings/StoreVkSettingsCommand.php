<?php

declare(strict_types=1);

namespace Module\Source\Vk\Actions\Settings;

use Illuminate\Http\Request;
use Module\Source\Vk\Data\AccountData;
use Module\Source\Vk\Data\CampaignData;
use Module\Source\Vk\Data\ClientData;
use Module\Source\Vk\Data\WebhookData;
use Spatie\LaravelData\DataCollection;

final class StoreVkSettingsCommand
{
    public function __construct(
        public readonly AccountData $account,
        public readonly ?ClientData $client,
        public readonly ?DataCollection $campaigns,
        public readonly string $uuid,
        public readonly ?DataCollection $webhooks,
        public readonly bool $is_vk_lead_messages,
        public readonly bool $is_vk_lead_forms,
    ) {
    }

    public static function fromRequest(Request $request): StoreVkSettingsCommand
    {
        $client = $request->input('client');
        $webhooks = $request->get('webhooks');

        return new self(
            account: AccountData::from($request->input('account')),
            client: $client
                ? ClientData::from($client)
                : null,
            campaigns: is_null($request->input('campaigns'))
                ? null
                : CampaignData::collection($request->input('campaigns')),
            uuid: $request->input('key'),
            webhooks: empty($webhooks)
                ? null
                : WebhookData::collection($webhooks),
            is_vk_lead_messages: filled($request->input('is_vk_lead_messages'))
                ? $request->input('is_vk_lead_messages')
                : false,
            is_vk_lead_forms: filled($request->input('is_vk_lead_forms'))
                ? $request->input('is_vk_lead_forms')
                : true,
        );
    }

    /**
     * @return array{account: \Module\Source\Vk\Data\AccountData, client: \Module\Source\Vk\Data\ClientData|null, campaigns: \Spatie\LaravelData\DataCollection|null, uuid: string, webhooks: \Spatie\LaravelData\DataCollection|null, is_vk_lead_messages: bool, is_vk_lead_forms: bool}
     */
    public function settingsAttributes(): array
    {
        return [
            'account' => $this->account,
            'client' => $this->client,
            'campaigns' => $this->campaigns,
            'uuid' => $this->uuid,
            'webhooks' => $this->webhooks,
            'is_vk_lead_messages' => $this->is_vk_lead_messages,
            'is_vk_lead_forms' => $this->is_vk_lead_forms,
        ];
    }
}
