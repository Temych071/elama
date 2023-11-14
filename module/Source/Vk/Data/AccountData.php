<?php

declare(strict_types=1);

namespace Module\Source\Vk\Data;

use Spatie\LaravelData\Data;

final class AccountData extends Data
{
    public const ACCOUNT_TYPE_GENERAL = 'general';
    public const ACCOUNT_TYPE_AGENCY = 'agency';

    public function __construct(
        public readonly string $access_role,
        public readonly int $account_id,
        public readonly string $account_type,
        public readonly int $account_status,
        public readonly string $account_name,
        public readonly bool $can_view_budget,
    ) {
    }
}
