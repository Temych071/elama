<?php

declare(strict_types=1);

namespace Loyalty\Actions;

use Illuminate\Support\Facades\RateLimiter;
use Loyalty\Jobs\UpdateWalletClassJob;
use Loyalty\Models\Loyalty;

final class DispatchUpdateWalletClassAction
{
    private const JOB_DELAY = 10;
//    private const JOB_DELAY = 2 * 60;

    public function execute(Loyalty $loyalty, bool $withoutDelay = false): void
    {
        RateLimiter::attempt(
            key: "loyalty.$loyalty->id.wallet.update",
            maxAttempts: 1,
            callback: static fn() => dispatch(new UpdateWalletClassJob($loyalty->id))
                ->delay($withoutDelay ? 0 : self::JOB_DELAY),
            decaySeconds: self::JOB_DELAY,
        );
    }
}
