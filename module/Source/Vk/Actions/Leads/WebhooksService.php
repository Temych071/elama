<?php

declare(strict_types=1);

namespace Module\Source\Vk\Actions\Leads;

use App\Exceptions\BusinessException;
use Module\Source\Vk\Data\WebhookData;
use Module\Source\Vk\Models\VkLead;
use Module\Source\Vk\Models\VkSettings;

final class WebhooksService
{
    public const TYPE_CONFIRMATION = 'confirmation';
    public const TYPE_NEW_LEAD_FORM = 'lead_forms_new';
    public const TYPE_NEW_MESSAGE = 'message_new';

    public function getConfirmationCode(string $key, int $groupId): string
    {
        $settings = $this->findSettings($key);

        return $this->findWebhookOrFail($settings, $groupId)->code;
    }

    public function storeNewLeadForm(string $key, int $groupId, array $data): ?VkLead
    {
        $settings = $this->findSettings($key);

        if (!$settings->source) {
            return null;
        }

        $this->findWebhookOrFail($settings, $groupId);

        return VkLead::query()->create([
            'object' => $data,
            'group_id' => $groupId,
            'user_id' => $data['user_id'],
            'type' => self::TYPE_NEW_LEAD_FORM,
            'source_id' => $settings->source->id,
        ]);
    }

    public function storeNewMessage(string $key, int $groupId, array $data): ?VkLead
    {
        $settings = $this->findSettings($key);

        if (!$settings->source) {
            return null;
        }

        $this->findWebhookOrFail($settings, $groupId);

        $sourceId = $settings->source->id;

        $userId = $data['message']['from_id'] ?? $data['user_id'];

        $isAlreadyExists = VkLead::query()
            ->where('source_id', $sourceId)
            ->where('user_id', $userId)
            ->where('type', self::TYPE_NEW_MESSAGE)
            ->exists();

//        Log::build([
//            'driver' => 'single',
//            'path' => storage_path('logs/vk-webhooks.log'),
//        ])->info('Store new message', [
//            'source_id' => $sourceId,
//            'is_already_exists' => $isAlreadyExists,
//            'data' => $data,
//        ]);

        if ($isAlreadyExists) {
            return null;
        }

        return VkLead::query()->create([
            'object' => $data,
            'group_id' => $groupId,
            'user_id' => $userId,
            'type' => self::TYPE_NEW_MESSAGE,
            'source_id' => $sourceId,
            'ref' => $data['message']['ref'] ?? null,
            'ref_source' => $data['message']['ref_source'] ?? null,
        ]);
    }


    private function findWebhookOrFail(VkSettings $settings, int $groupId): WebhookData
    {
        $webhook = $settings->webhooks?->toCollection()->firstWhere('group_id', $groupId);

        if (!$webhook instanceof \Module\Source\Vk\Data\WebhookData) {
            throw new BusinessException('This group does not exists');
        }

        return $webhook;
    }

    public function findSettings(string $key): VkSettings
    {
        return VkSettings::query()->where('uuid', $key)->firstOrFail();
    }
}
