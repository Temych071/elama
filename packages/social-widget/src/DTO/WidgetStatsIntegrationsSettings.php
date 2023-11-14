<?php

declare(strict_types=1);

namespace SocialWidget\DTO;

use Spatie\LaravelData\Data;

final class WidgetStatsIntegrationsSettings extends Data
{

    public function __construct(
        public bool $ym_enabled = false,
        public ?string $ym_counter_id = '',
        public bool $ym_auto_goal = true,

        public bool $ga_enabled = false,
        public ?string $ga_counter_id = '',
    ) {
    }
}
