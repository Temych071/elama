<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\Vk;

use Illuminate\Http\Request;
use Module\Source\Vk\Actions\Leads\WebhooksService;

final class VkWebhookController
{
    public function __invoke(Request $request, WebhooksService $service): string
    {
        $params = $request->validate([
            'key' => 'required|uuid',
            'type' => 'required|string',
            'group_id' => 'required'
        ]);

        $type = $params['type'];
        $obj = $request->get('object');

        if ($type === WebhooksService::TYPE_CONFIRMATION) {
            return $service->getConfirmationCode($params['key'], $params['group_id']);
        }

        if ($type === WebhooksService::TYPE_NEW_LEAD_FORM) {
            $service->storeNewLeadForm($params['key'], $params['group_id'], $obj);
        }

        if ($type === WebhooksService::TYPE_NEW_MESSAGE) {
            $service->storeNewMessage($params['key'], $params['group_id'], $obj);
        }

//        throw new BusinessException('Unexpected webhook type');

        return 'ok';
    }
}
