<?php

declare(strict_types=1);

namespace Module\Source\Vk\Jobs\Middleware;

use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Support\Facades\Redis;

final class VkFetchingThrottle
{
    private const INTERVAL = 5;

    /**
     * @throws LimiterTimeoutException
     */
    public function handle(mixed $job, callable $next): void
    {
        Redis::throttle('key')
            ->block(0)->allow(1)->every(self::INTERVAL)
            ->then(function () use ($job, $next): void {
                $next($job);
            }, function () use ($job): void {
                $job->release(self::INTERVAL);
            });
    }
}
