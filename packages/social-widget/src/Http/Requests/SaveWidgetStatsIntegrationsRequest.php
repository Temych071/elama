<?php

declare(strict_types=1);

namespace SocialWidget\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class SaveWidgetStatsIntegrationsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ym_enabled' => ['required', 'boolean'],
            'ym_counter_id' => ['required_if:ym_enabled,true', 'nullable', 'string', 'max:32'],
            'ym_auto_goal' => ['required_if:ym_enabled,true', 'boolean'],

            'ga_enabled' => ['required', 'boolean'],
            'ga_counter_id' => ['required_if:ga_enabled,true', 'nullable', 'string', 'max:32'],
        ];
    }

    public function messages(): array
    {
        return [
            'ym_counter_id.required_if' => 'Поле номер счетчика, обязательно для заполнения',
            'ga_counter_id.required_if' => 'Поле номер счетчика, обязательно для заполнения',
        ];
    }
}
