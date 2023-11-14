<?php

namespace Module\Billing\Payments\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Module\Billing\Payments\Actions\RunAutoRefillAction;
use Module\User\Models\User;

class RunAutoRefillJob implements ShouldQueue, ShouldBeUnique
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $uniqueFor = 60;

    public function __construct(
        public User $user
    ) {
    }

    public function handle(): void
    {
        app(RunAutoRefillAction::class)
            ->execute($this->user);
    }

    public function uniqueId()
    {
        return $this->user->id;
    }
}
