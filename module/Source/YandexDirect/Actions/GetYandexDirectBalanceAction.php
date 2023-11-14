<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions;

use Illuminate\Support\Facades\DB;
use Module\Source\DTO\CabinetBalance;
use Module\Source\Sources\Models\Source;

final class GetYandexDirectBalanceAction
{
    public function execute(Source $source): ?CabinetBalance
    {
        $settings_id = $source->settings_id;

        $res = DB::table('yandex_direct_accounts')
            ->select(['currency', 'amount', 'daily_budget', 'daily_spend_mode'])
            ->where('settings_id', $settings_id)
            ->latest()
            ->first();

        if (is_null($res)) {
            return null;
        }

        return new CabinetBalance(
            currency: $res->currency,
            amount: $res->amount / 1_000_000,
            dailyBudget: $res->daily_budget / 1_000_000,
            dailyType: $res->daily_spend_mode,
        );
    }
}
