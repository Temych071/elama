<?php

declare(strict_types=1);

namespace Module\Source\Actions;

use Module\Campaign\Models\Campaign;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Actions\Account\GetVkBudgetAction;
use Module\Source\YandexDirect\Actions\Fetch\GetAccountAction as GetYandexDirectBalanceAction;

final class GetCabinetBalancesAction
{
    public function execute(Campaign $campaign): array
    {
        /** @var Source[] $cabinets */
        $cabinets = $campaign->cabinetSources;

        $balances = [];
        foreach ($cabinets as $cabinet) {
            $balance = match ($cabinet->settings_type) {
                // Для тестов
//                Source::TYPE_YANDEX_DIRECT => [
//                    'amount' => 1123,
//                    'currency' => 'RUB',
//                ],
                Source::TYPE_YANDEX_DIRECT => app(GetYandexDirectBalanceAction::class)->execute($cabinet, false),
                Source::TYPE_VK => $this->getVkBalance($cabinet),
                default => null,
            };

            if (!is_null($balance)) {
                if (isset($balances[$cabinet->settings_type])) {
                    $balances[$cabinet->settings_type]['amount'] = $balance['amount'];
                } else {
                    $balances[$cabinet->settings_type] = $balance;
                }
            }
        }

        return $balances;
    }

    private function getVkBalance(Source $source): ?array
    {
        $balance = app(GetVkBudgetAction::class)->execute($source);

        if ($balance === null) {
            return null;
        }

        return [
            'currency' => 'RUB',
            'amount' => $balance,
        ];
    }
}
