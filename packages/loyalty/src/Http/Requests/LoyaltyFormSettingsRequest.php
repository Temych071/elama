<?php

declare(strict_types=1);

namespace Loyalty\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Loyalty\DTO\LoyaltyFormSettings;
use Loyalty\Validation\Rules\HexColor;

final class LoyaltyFormSettingsRequest extends FormRequest
{
    public function rules(): array
    {
        $makeFieldRules = static fn(string $key) => [
            $key => ['required', 'array'],
            "$key.title" => ['required', 'string', 'max:255'],
            "$key.required" => ['required', 'boolean'],
        ];

        return [
            'company_name' => ['required', 'max:256'],
            'company_desc' => ['required', 'max:256'],

            'header_color' => ['required', new HexColor()],
            'title' => ['required', 'max:256'],
            'button_text' => ['required', 'max:128'],
            'button_color' => ['required', new HexColor()],

            // Или лучше переделать на валидацию в спатиевских DTOшках?
            ...$makeFieldRules('name_field'),
            ...$makeFieldRules('surname_field'),
            ...$makeFieldRules('email_field'),
            ...$makeFieldRules('gender_field'),
            ...$makeFieldRules('birthday_field'),

            'sms_required' => ['required', 'boolean'],
            'email_required' => ['required', 'boolean'],
            'terms_required' => ['required', 'boolean'],
            'custom_terms' => ['nullable', 'string', 'max:1024'],

            'custom_fields' => ['array'],
            'custom_fields.*' => ['array'],
            'custom_fields.*.title' => ['required', 'string', 'max:255'],
            'custom_fields.*.key' => ['required', 'string', 'max:255'],
            'custom_fields.*.required' => ['boolean'],

            'logo' => ['nullable', 'file', 'mimetypes:image/png,image/jpeg,image/svg+xml', 'max:2024'/* 2Mb */],
            'logo_del' => ['nullable', 'boolean'],
        ];
    }

    public function getFormSettings(): LoyaltyFormSettings
    {
        return LoyaltyFormSettings::from($this->validated());
    }
}
