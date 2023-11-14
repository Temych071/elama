<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions\Fetch;

use Illuminate\Support\Facades\DB;
use Module\Campaign\Models\Campaign;

final class FetchAccountsAction
{
    public function execute(Campaign $campaign): void
    {
        $res = app(GetAccountAction::class)->execute($campaign->yandexDirectSource);

        DB::table('yandex_direct_accounts')
            ->insert($res);
    }
}
