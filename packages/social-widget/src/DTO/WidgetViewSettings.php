<?php

declare(strict_types=1);

namespace SocialWidget\DTO;

use Illuminate\Support\Facades\Storage;
use SocialWidget\Enums\RootBtnType;
use SocialWidget\Enums\WidgetPosition;
use Spatie\LaravelData\Data;

final class WidgetViewSettings extends Data
{
    public function __construct(
        public WidgetPosition $position = WidgetPosition::RIGHT,
        public int $margin_x = 25,
        public int $margin_y = 25,

        public ?string $avatar_path = null,
        public int $avatar_size = 64, // root btn size
        public string $avatar_border_color = '#1975FF',

        public bool $welcome_enabled = true,
        public string $welcome_message = 'Здравствуйте! У вас есть вопрос или нужна консультация?',
        public int $welcome_delay = 30,

        public bool $popup_enabled = true,
        public string $popup_title = 'Николай Иванов',
        public string $popup_message = 'Эксперт компании',
        public ?string $popup_phone = '+7 (900) 100-10-10',

        public bool $disable_copyright = false,
        public RootBtnType $root_btn_type = RootBtnType::PHOTO,
    ) {
    }

    /**
     * @return array{avatar_url: mixed}
     */
    public function with(): array
    {
        return [
            'avatar_url' => $this->avatar_path === null
                ? url('social-widget/img/default-avatar.png')
                : Storage::disk('public')->url($this->avatar_path),
        ];
    }
}
