<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions;

use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Actions\Fetch\GetBidModifiersAction;
use Module\Source\YandexDirect\Models\DirectCampaign;

final class CheckBidModifiersAction
{
    public function execute(Source $source): void
    {
        $bidModifiers = app(GetBidModifiersAction::class)->execute($source);

        $res = [];
        foreach ($bidModifiers as $bidModifier) {
            $key = (int) $bidModifier['CampaignId'];
            if (!isset($res[$key])) {
                $res[$key] = 0;
            }

            $res[$key]++;
        }

        $source->settings->directCampaignsSel
            ->each(static function (DirectCampaign $directCampaign) use ($res): void {
                $directCampaign->bid_modifiers_num = $res[(int) $directCampaign->campaign_id] ?? 0;
                $directCampaign->save();
            });
    }
}
