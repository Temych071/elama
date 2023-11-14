<?php

namespace Module\Campaign\Actions;

use Illuminate\Support\Facades\Cache;
use Module\Campaign\Models\Campaign;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Checks\GetCheckResultsAction as GetVkCheckResultsAction;
use Module\Source\YandexDirect\Checks\GetCheckResultsAction as GetYandexDirectCheckResultsAction;
use Spatie\LaravelData\DataCollection;

final class GetAuditAction
{
    public const CACHE_KEY = 'checks:result:without-filters:';
    public const CACHE_TTL = 15 * 60;

    public function handle(Campaign $campaign): array
    {
        $checks = Cache::remember(
            key: self::CACHE_KEY . $campaign->id,
            ttl: self::CACHE_TTL,
            callback: fn (): array => $this->getResult($campaign),
        );

        return array_map(static function ($groups): int|float {
            $reducedChecks = array_reduce($groups, static function ($ax, ?DataCollection $group): array {
                $reducedGroup = array_reduce($group?->all() ?? [], static fn($ax, $dx): array => [
                    'total' => $ax['total'] + $dx->totalObjectsCount,
                    'failed' => $ax['failed'] + (is_countable($dx->failedObjects) ? count($dx->failedObjects) : 0),
                ], ['failed' => 0, 'total' => 0]);

                return [
                    'total' => $reducedGroup['total'] + $ax['total'],
                    'failed' => $reducedGroup['failed'] + $ax['failed'],
                ];
            }, ['failed' => 0, 'total' => 0]);

            if (!$reducedChecks['total']) {
                return 1;
            }

            return 1 - ($reducedChecks['failed'] / $reducedChecks['total']);
        }, $checks);
    }

    private function getResult(Campaign $campaign): array
    {
        $checks = [];

        if ($campaign->yandexDirectSource?->settings) {
            $checks[Source::TYPE_YANDEX_DIRECT] = app(GetYandexDirectCheckResultsAction::class)
                ->execute($campaign->yandexDirectSource, []);
        }

        if ($campaign->vkSource?->settings) {
            $checks[Source::TYPE_VK] = app(GetVkCheckResultsAction::class)
                ->execute($campaign->vkSource, []);
        }

        return $checks;
    }
}
