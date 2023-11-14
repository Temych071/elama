<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Account;

use Illuminate\Support\Facades\Cache;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Actions\Fetch\GetAccountAction as GetYandexDirectBalanceAction;
use Module\Source\YandexDirect\Models\DirectCampaign;
use Module\Source\YandexDirect\Models\YandexDirectSettings;

final class LowBalanceCheckRule extends YandexDirectAccountCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'low-balance.title';
    protected string $desc = 'low-balance.desc';
    protected string $message = 'low-balance.message';

    /**
     * @param  YandexDirectSettings  $object
     */
    public function check($object): bool
    {
        $source = $object->source;
        $campaignsBudget = $object
            ->directCampaigns
            ->sum(static fn (DirectCampaign $directCampaign): int => $directCampaign->daily_budget_amount);

        $balance = Cache::remember(
            "checks:balances:" . Source::TYPE_YANDEX_DIRECT . ":{$source->authToken->user_id}",
            15 * 60,
            static fn () => app(GetYandexDirectBalanceAction::class)->execute($source),
        );

        $budget = $balance['daily_budget'] ?? $campaignsBudget;

        return $balance['amount'] >= ($budget * 3);
    }
}
