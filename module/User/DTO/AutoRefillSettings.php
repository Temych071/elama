<?php

namespace Module\User\DTO;

use Spatie\LaravelData\Data;

class AutoRefillSettings extends Data
{
    public function __construct(
        public ?int $payment_method_id = null,
        public int $amount = 5000,
        public int $limit = 1000,
        public bool $enabled = true,
    ){
    }
}
