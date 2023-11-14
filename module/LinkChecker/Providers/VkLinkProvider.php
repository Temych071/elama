<?php

declare(strict_types=1);

namespace Module\LinkChecker\Providers;

use Illuminate\Support\Arr;
use Module\LinkChecker\Dto\LinkCheckItemDto;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Models\VkAdParam;

final class VkLinkProvider extends LinkProvider
{
    public function getLinkList($sourceId): iterable
    {
        $settingsId = Source::findOrFail($sourceId)->settings_id;

        $ads = VkAdParam::query()
            ->where('setting_id', $settingsId)
            ->where('status', VkAdParam::STATUS_STARTED)
            ->whereNotNull('link_url')
            ->get(['id', 'link_url', 'title'])
            ->filter(fn (VkAdParam $it): bool => !empty($it->link_url) && !str_contains($it->link_url, '//vk.com'))
            ->map(fn (VkAdParam $it): array => [
                'url' => $this->filterLink($it->link_url),
                'title' => $it->title,
                'cabinet_link' => "https://vk.com/ads?act=office&union_id=$it->id",
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
