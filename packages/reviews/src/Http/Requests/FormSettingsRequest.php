<?php

declare(strict_types=1);

namespace Reviews\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Reviews\Enums\ReviewFormType;
use Loyalty\Validation\Rules\HexColor;

final class FormSettingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'min_stars_for_publish' => ['nullable', 'numeric', 'integer', 'min:1', 'max:5'],
            'max_stars_for_notification' => ['nullable', 'numeric', 'integer', 'min:1', 'max:5'],

            'phrases' => ['nullable', 'array'],
            'phrases.*' => ['nullable', 'string', 'max:1000'],

            'page_settings' => ['nullable', 'array'],
            'page_settings.fields' => ['nullable', 'array'],
            'page_settings.fields.*' => ['array'],
            'page_settings.fields.*.show' => ['required', 'boolean'],
            'page_settings.fields.*.required' => ['required', 'boolean'],

            'page_settings.colors' => ['nullable', 'array'],
            'page_settings.colors.background' => ['nullable', 'string', new HexColor()],
            'page_settings.colors.buttons' => ['nullable', 'string', new HexColor()],
            'page_settings.colors.buttonsText' => ['nullable', 'string', new HexColor()],
            'page_settings.colors.messengersButtons' => ['nullable', 'string', new HexColor()],

            'page_settings.policy' => ['nullable', 'boolean'],
            'page_settings.policyLink' => ['exclude_if:page_settings.policy,false', 'string', 'url', 'max:1000'],

            'page_settings.show_reviews_list' => ['nullable', 'boolean'],

            'messengers' => ['nullable', 'array', 'max:5'],
            'messengers.*' => ['array'],
            'messengers.*.title' => ['required', 'string', 'max:200'],
            'messengers.*.link' => ['required', 'string', 'max:1000'],

            'external_aggregators' => ['nullable', 'array', 'max:5'],
            'external_aggregators.*' => ['array'],
            'external_aggregators.*.title' => ['required', 'string', 'max:200'],
            'external_aggregators.*.link' => ['required', 'string', 'max:1000'],

            'logo' => ['nullable', 'file', 'mimetypes:image/png,image/jpeg,image/svg+xml', 'max:2024'/* 2Mb */],
            'logo_del' => ['nullable', 'boolean'],
            'banner' => ['nullable', 'file', 'mimetypes:image/png,image/jpeg', 'max:2024'/* 2Mb */],
            'banner_del' => ['nullable', 'boolean'],
            'banner_link' => ['nullable', 'string', 'url', 'max:1000'],

            'thx_banner' => ['nullable', 'file', 'mimetypes:image/png,image/jpeg', 'max:2024'/* 2Mb */],
            'thx_banner_del' => ['nullable', 'boolean'],
            'thx_banner_link' => ['nullable', 'string', 'url', 'max:1000'],
            'thx_button_link' => ['nullable', 'string', 'url', 'max:1000'],

            'type' => ['required', new Enum(ReviewFormType::class)],

            'yandex_company_id' => ['nullable', 'numeric', 'integer'],
            'widget_yamaps' => ['nullable', 'numeric', 'integer'],
            'widget_2gis' => ['nullable', 'numeric'],
        ];
    }
}
