<?php

declare(strict_types=1);

namespace SocialWidget\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class SaveWidgetCrmIntegrationsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
//            'amo_enabled' => ['required', 'boolean'],
//            'amo_responsible_id' => ['required_if:amo_enabled,true', 'nullable', 'string', 'max:32'],
//            'amo_kanban_id' => ['required_if:amo_enabled,true', 'nullable', 'string', 'max:32'],
//            'amo_send_utms' => ['required_if:amo_enabled,true', 'nullable', 'boolean'],

            'bx_enabled' => ['required', 'boolean'],
            'bx_webhook_url' => ['required_if:bx_enabled,true', 'nullable', 'string', 'max:256'],
//            'bx_responsible_id' => ['required_if:bx_enabled,true', 'nullable', 'string', 'max:32'],
//            'bx_kanban_id' => ['required_if:bx_enabled,true', 'nullable', 'string', 'max:32'],
            'bx_send_utms' => ['required_if:bx_enabled,true', 'nullable', 'boolean'],
        ];
    }
}
