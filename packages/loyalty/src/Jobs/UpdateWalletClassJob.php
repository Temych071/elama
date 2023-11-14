<?php

namespace Loyalty\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Loyalty\Models\Loyalty;

class UpdateWalletClassJob implements ShouldQueue, ShouldBeUnique
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $uniqueFor = 60;

    public function __construct(
        public string $loyaltyId
    ) {
    }

    public function handle(): void
    {
        $loyalty = Loyalty::query()->findOrFail($this->loyaltyId);

        foreach ($loyalty->getWalletServices() as $service) {
            $service->createOrUpdateWalletClass($loyalty);
        }
    }

    public function uniqueId()
    {
        return $this->loyaltyId;
    }
}
