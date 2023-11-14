<?php

declare(strict_types=1);

namespace Loyalty\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Loyalty\DTO\LoyaltyCardSettings;
use Loyalty\Validation\Rules\HexColor;

final class LoyaltyCardSettingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'max:256'],
            'desc' => ['required', 'max:1024'],

            'header_color' => ['required', new HexColor()],

            'show_name' => ['required', 'boolean'],
            'show_balance' => ['required', 'boolean'],
            'show_next_visit' => ['required', 'boolean'],
            'show_discount' => ['required', 'boolean'],

            'discount_percent' => ['required', 'numeric', 'integer'],

            'logo' => ['nullable', 'file', 'mimetypes:image/png,image/jpeg,image/svg+xml', 'max:2024'/* 2Mb */],
            'logo_del' => ['nullable', 'boolean'],

            'banner' => ['nullable', 'file', 'mimetypes:image/png,image/jpeg,image/svg+xml', 'max:2024'/* 2Mb */],
            'banner_del' => ['nullable', 'boolean'],
        ];
    }

    public function getCardSettings(): LoyaltyCardSettings
    {
        return LoyaltyCardSettings::from($this->validated());
    }
}
