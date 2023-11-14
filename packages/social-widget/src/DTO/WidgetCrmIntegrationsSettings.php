<?php

declare(strict_types=1);

namespace SocialWidget\DTO;

use Spatie\LaravelData\Data;

final class WidgetCrmIntegrationsSettings extends Data
{
    public function __construct(
        public bool $amo_enabled = false,
        public ?string $amo_responsible_id = null,
        public ?string $amo_kanban_id = null,
        public bool $amo_send_utms = true,

        public bool $bx_enabled = false,
        public ?string $bx_webhook_url = null,
        public ?string $bx_responsible_id = null,
        public ?string $bx_kanban_id = null,
        public bool $bx_send_utms = true,
    ) {
    }
}
