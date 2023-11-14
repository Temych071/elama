<?php

declare(strict_types=1);

namespace Module\Admin\Settings;

use Spatie\LaravelSettings\Settings;

final class BillingSettings extends Settings
{
    public int $trial_balance = 0;
    public ?int $initial_plan_id = null;

    public static function group(): string
    {
        return 'billing';
    }
}
