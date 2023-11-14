<?php

declare(strict_types=1);

namespace Module\Source\Sources\Actions\DataUpdate;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Module\Campaign\Models\Campaign;
use Module\Source\Sources\Enums\FetchingDataStatus;
use Module\Source\Sources\Models\Source;

final class SourceDataUpdateService
{
    public function getCampaignSourcesStatus(Campaign $campaign): FetchingDataStatus
    {
        $statuses = Source::query()
            ->where('campaign_id', $campaign->id)
            ->pluck('data_status');

        if ($statuses->contains(FetchingDataStatus::fetching)) {
            return FetchingDataStatus::fetching;
        }

        if ($statuses->contains(FetchingDataStatus::error)) {
            return FetchingDataStatus::error;
        }

        return FetchingDataStatus::updated;
    }

    public function getNextFetchingDate(Campaign $campaign): CarbonInterface
    {
        $minUpdatedDate = $campaign->sources->map->data_updated_at->min() ?? Carbon::now();

        return $minUpdatedDate->addHours(Source::MIN_UPDATE_INTERVAL)->toImmutable();
    }

    public function isUpdateAvailable(Campaign $campaign): bool
    {
        $sources = $campaign->sources;

        if (empty($sources)) {
            return false;
        }

        if ($sources->map->data_status->contains(FetchingDataStatus::fetching)) {
            return false;
        }

        $minUpdateDate = $sources->map->data_updated_at->min();

        if ($minUpdateDate === null) {
            return true;
        }

        $timeLeft = $minUpdateDate->diffInHours();

        return $timeLeft >= Source::MIN_UPDATE_INTERVAL;
    }
}
