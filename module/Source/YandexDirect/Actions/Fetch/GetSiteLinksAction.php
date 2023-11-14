<?php

namespace Module\Source\YandexDirect\Actions\Fetch;

use Module\Source\Sources\Exceptions\UnsupportedSourceTypeException;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Models\DirectAd;
use Module\Source\YandexDirect\Services\YandexDirectService;

class GetSiteLinksAction
{
    public function execute(Source $source): array
    {
        UnsupportedSourceTypeException::throwIfTypeNotIn($source, Source::TYPE_YANDEX_DIRECT);

        $service = new YandexDirectService($source->authToken);

        $siteLinksSetIdsToAds = $source->settings
            ->directAdsSel
            ->filter(static fn (DirectAd $directAd): bool => !empty($directAd->other['SitelinkSetId']))
            ->mapWithKeys(static fn (DirectAd $directAd): array => [
                $directAd->other['SitelinkSetId'] => $directAd->id,
            ]);

        $siteLinksSetIds = $siteLinksSetIdsToAds->keys()->toArray();

        if ($siteLinksSetIds === []) {
            return [];
        }

        $siteLinksSets = $service->getSitelinksSets($siteLinksSetIds);
        foreach ($siteLinksSets as &$siteLinksSet) {
            $siteLinksSet['AdId'] = $siteLinksSetIdsToAds[$siteLinksSet['Id']];
        }
        unset($siteLinksSet);

        return $siteLinksSets;
    }
}
