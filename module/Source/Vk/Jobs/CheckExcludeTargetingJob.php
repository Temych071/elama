<?php

declare(strict_types=1);

namespace Module\Source\Vk\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Actions\Fetch\CheckAdsTargetAction;
use Module\Source\Vk\Jobs\Middleware\VkFetchingThrottle;

final class CheckExcludeTargetingJob implements ShouldQueue, ShouldBeUnique
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The number of seconds after which the job's unique lock will be released.
     */
    public int $uniqueFor = 216_000; // 1 hour

    public function __construct(public int $sourceId)
    {
        $this->onQueue('vk');
    }

    public function middleware(): array
    {
        return [new VkFetchingThrottle()];
    }

    public function handle(): void
    {
        app(CheckAdsTargetAction::class)
            ->execute(Source::query()->findOrFail($this->sourceId));
    }

    public function uniqueId(): string
    {
        return (string)$this->sourceId;
    }
}
