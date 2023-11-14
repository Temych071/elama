<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions;

use Illuminate\Support\Str;
use Module\Campaign\Models\Campaign;
use Module\Source\YandexDirect\Actions\Fetch\GetKeywordsAction;
use Module\Source\YandexDirect\Models\DirectAd;

final class CheckKeywordsAction
{
    public function execute(Campaign $campaign): void
    {
        $kws = collect(app(GetKeywordsAction::class)->execute($campaign))
            ->groupBy('AdGroupId')
            ->map(static fn ($group): \Illuminate\Support\Collection => $group->map(static fn ($kw) => $kw['Keyword']));

        $groupIds = $kws->keys();

        $ads = DirectAd::query()
            ->where('settings_id', $campaign->yandexDirectSource->settings_id)
            ->whereIn('ad_group_id', $groupIds)
            ->get()
            ->groupBy('ad_group_id');

        $adsForSave = [];
        foreach ($ads as $groupId => $group) {
            if (is_null($groupId)) {
                continue;
            }

            $kws = ($kws[$groupId] ?? null)?->toArray();
            if (is_null($kws)) {
                continue;
            }

//            dd($kws);

            /** @var DirectAd $ad */
            foreach ($group as $ad) {
                $textAndTitle = ($ad->other['Title'] ?? '')
                    . '|' . ($ad->other['Title2'] ?? '')
                    . '|' . ($ad->other['Text'] ?? '');
                $dUrl = ($ad->other['DisplayDomain'] ?? '') . '|' . ($ad->other['DisplayUrlPath'] ?? '');

                $ad->has_kws_in_title = Str::contains($textAndTitle, $kws);
                $ad->has_kws_in_durl = Str::contains($dUrl, $kws);

                $ad->save();
//                $adsForSave[] = $ad;
            }
        }

//        dd($adsForSave);
//        DirectAd::query()->update($adsForSave);
    }
}
