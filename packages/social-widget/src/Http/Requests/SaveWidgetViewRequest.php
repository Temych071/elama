<?php

declare(strict_types=1);

namespace SocialWidget\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use SocialWidget\Enums\RootBtnType;
use SocialWidget\Enums\WidgetPosition;
use SocialWidget\Validation\Rules\HexColor;

final class SaveWidgetViewRequest extends FormRequest
{

    /**
     * @return array{position: Enum[]|string[], margin_x: string[], margin_y: string[], avatar: string[], avatar_size: string[], avatar_border_color: HexColor[]|string[], welcome_enabled: string[], welcome_message: string[], welcome_delay: string[], popup_enabled: string[], popup_title: string[], popup_message: string[], popup_phone: string[]}
     */
    public function rules(): array
    {
        return [
            'position' => ['required', new Enum(WidgetPosition::class)],
            'margin_x' => ['required', 'numeric', 'integer', 'min:0'],
            'margin_y' => ['required', 'numeric', 'integer', 'min:0'],

            'avatar' => ['nullable', 'file', 'mimetypes:image/png,image/jpeg,image/svg+xml', 'max:2024'/* 2Mb */],
            'avatar_size' => ['required', 'numeric', 'integer', 'min:40', 'max:80'],
            'avatar_border_color' => ['required', new HexColor()],

            'welcome_enabled' => ['required', 'boolean'],
            'welcome_message' => ['required_if:welcome_enabled,true', 'string', 'max:1024'],
            'welcome_delay' => ['required_if:welcome_enabled,true', 'numeric', 'integer', 'min:0'],

            'popup_enabled' => ['required', 'boolean'],
            'popup_title' => ['required_if:popup_enabled,true', 'string', 'max:256'],
            'popup_message' => ['required_if:popup_enabled,true', 'string', 'max:2048'],
            'popup_phone' => ['nullable', 'string', 'max:256'],

            'disable_copyright' => ['required', 'boolean'],
            'root_btn_type' => ['required', new Enum(RootBtnType::class)],
        ];
    }
}
