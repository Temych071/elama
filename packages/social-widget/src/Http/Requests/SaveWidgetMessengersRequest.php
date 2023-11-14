<?php

declare(strict_types=1);

namespace SocialWidget\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use SocialWidget\Enums\WhatsappRedirectType;

final class SaveWidgetMessengersRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'wa_enabled' => ['required', 'boolean'],
            'wa_phone' => ['required_if:wa_enabled,true', 'string'],
            'wa_message' => ['nullable', 'string'],
            'wa_redirect_type' => ['required', new Enum(WhatsappRedirectType::class)],

            'tg_enabled' => ['required', 'boolean'],
            'tg_nickname' => ['required_if:tg_enabled,true', 'string'],
            'tg_dont_create_leads' => ['required_if:tg_enabled,true', 'boolean'],
        ];
    }
}
