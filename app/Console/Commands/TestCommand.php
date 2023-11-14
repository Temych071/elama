<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Module\Billing\Account\Models\Transaction;
use Module\Billing\Events\LowBalanceEvent;
use Module\Billing\Events\NegativeBalanceEvent;
use Module\Billing\Events\NewSubscriptionEvent;
use Module\Billing\Events\NewTransactionEvent;
use Module\User\Models\User;
use Reviews\Enums\ReviewSource;
use Reviews\Events\NewExternalReviewsEvent;
use Reviews\Models\ReviewForm;

class TestCommand extends Command
{
    protected $signature = 'test:run';
    protected $description = 'Command for testing something';

    public function handle(): int
    {
        if (!config('app.debug')) {
            return 1;
        }

//        event(new LowBalanceEvent(User::findOrFail(1), 123));
//        event(new NegativeBalanceEvent(User::findOrFail(1)));
//        event(new NewSubscriptionEvent(User::findOrFail(1)));
//        event(new NewTransactionEvent(Transaction::inRandomOrder()->first()));

        event(new NewExternalReviewsEvent(
            ReviewForm::inRandomOrder()->first(),
            ReviewSource::DAILY_GROW,
            123));

        return 0;
    }
}
