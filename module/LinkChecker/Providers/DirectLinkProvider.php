<?php

declare(strict_types=1);

namespace Module\LinkChecker\Providers;

use Illuminate\Support\Arr;
use Module\LinkChecker\Dto\LinkCheckItemDto;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Models\DirectAd;

final class DirectLinkProvider extends LinkProvider
{
    public function getLinkList($sourceId): iterable
    {
        $settingsId = Source::findOrFail($sourceId)->settings_id;

        $ads = DirectAd::query()
            ->where('settings_id', $settingsId)
            ->where('state', DirectAd::STATE_ON)
            ->whereNotNull('href')
            ->with('campaign:campaign_id,name')
            ->get(['id', 'href', 'campaign_id'])
            ->map(fn (DirectAd $it): array => [
                'url' => $this->filterLink($it->href),
                'title' => $it->campaign->name,
                'cabinet_link' => "https://direct.yandex.ru/dna/banners-edit?campaigns-ids=$it->campaign_id",
            ])
            ->groupBy('url')
            ->toArray();

        return array_values(
            Arr::map(
                $ads,
                static fn ($items, string $url): \Module\LinkChecker\Dto\LinkCheckItemDto => LinkCheckItemDto::from([
                    'url' => $url,
                    'ads' => $items,
                ]),
            ),
        );
    }
}
