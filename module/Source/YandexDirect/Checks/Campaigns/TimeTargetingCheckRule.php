<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Campaigns;

use Module\Source\YandexDirect\Models\DirectCampaign;

final class TimeTargetingCheckRule extends YandexDirectCampaignCheckRule
{
    private const RESULT_PASSED = 0;
    private const RESULT_NEVER_SHOW = 1;
    private const RESULT_NOT_SPECIFIED = 2;

    protected bool $textFromLang = true;
    protected string $title = 'time-targeting.title';
    protected string $desc = 'time-targeting.desc';
    protected string $message = 'time-targeting.message';

    public function check($object): bool
    {
        return self::getResultState($object) === self::RESULT_PASSED;
    }

    private static function getResultState(DirectCampaign $campaign): int
    {
        $schedule = $campaign->other['TimeTargeting']['Schedule']['Items'] ?? null;
        if (is_null($schedule)) {
            return self::RESULT_NOT_SPECIFIED;
        }

        $schedule = collect($schedule)
            ->map(static fn ($item): array => array_unique(
                array_slice(explode(',', (string) $item), 1),
                SORT_NUMERIC
            ))
            ->flatten()
            ->unique();

        if ($schedule->count() > 1) {
            return self::RESULT_PASSED;
        }

        $rate = (int)$schedule->first();

        if ($rate === 0) {
            return self::RESULT_NEVER_SHOW;
        }

        if ($rate === 100) {
            return self::RESULT_NOT_SPECIFIED;
        }

        return self::RESULT_PASSED;
    }
}
