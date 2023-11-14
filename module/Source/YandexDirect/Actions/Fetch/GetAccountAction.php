<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions\Fetch;

use Illuminate\Support\Carbon;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Models\YandexDirectSettings;
use Module\Source\YandexDirect\Services\YandexDirectLiveService;

final class GetAccountAction
{
    public function execute(Source $source, bool $rawCurrency = true): ?array
    {
        /** @var YandexDirectSettings $settings */
        $settings = $source->settings;

        if ($settings === null) {
            return null;
        }

        $service = new YandexDirectLiveService($source->authToken);

        $res = $service->accountManagement_Get();
        if ($res === []) {
            return null;
        }
        $res = $res[0];

        $ret = [
            'settings_id' => $settings->id,

            'account_id' => $res['AccountID'],
            'login' => $res['Login'],
            'email' => $res['EmailNotification']['Email'] ?? '-',
            'agency_name' => $res['AgencyName'] ?? '-',

            'currency' => $res['Currency'] ?? 'RUB',
            'amount' => $res['Amount'] * ($rawCurrency ? 1_000_000 : 1),
            'transfer_amount' => ($res['AmountAvailableForTransfer'] ?? 0) * ($rawCurrency ? 1_000_000 : 1),
            'discount' => $res['Discount'] ?? 0,

            'created_at' => Carbon::now(),
        ];


        if (!empty($res['AccountDayBudget'])) {
            $ret['daily_budget'] = $res['AccountDayBudget']['Amount'] * ($rawCurrency ? 1_000_000 : 1);
            $ret['daily_spend_mode'] = $res['AccountDayBudget']['SpendMode'];
        } else {
            $ret['daily_budget'] = 0;
            $ret['daily_spend_mode'] = "Disabled";
        }

        return $ret;
    }
}
