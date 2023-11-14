<?php

declare(strict_types=1);

namespace Loyalty\DTO;

use Spatie\LaravelData\Data;

final class LoyaltyCardSettings extends Data
{
    public function __construct(
        public string $title = 'Программа лояльности',
        public string $desc = 'Доступно только для начисления',

        public string $header_color = '#F6F8FA',

        public bool $show_name = true,
        public bool $show_balance = true,
        public bool $show_next_visit = false,
        public bool $show_discount = true,

        public int $discount_percent = 10,
    ) {
    }

}
