<?php

declare(strict_types=1);

namespace SocialWidget\DTO;

use SocialWidget\Enums\WhatsappRedirectType;
use Spatie\LaravelData\Data;

final class WidgetMessengersSettings extends Data
{
    public function __construct(
        public bool $wa_enabled = true,
        public string $wa_phone = '79001001010',
//        public ?string $wa_message = "Номер обращения: {client-id}. Здравствуйте, интересуют ваши услуги.",
        public ?string $wa_message = '',
        public WhatsappRedirectType $wa_redirect_type = WhatsappRedirectType::API,

        public bool $tg_enabled = true,
        public string $tg_nickname = 'telegram',
        public bool $tg_dont_create_leads = false,
    ) {
    }
}
