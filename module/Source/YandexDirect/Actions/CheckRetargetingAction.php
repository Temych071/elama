<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions;

use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Actions\Fetch\GetAudienceTargetsAction;
use Module\Source\YandexDirect\Models\DirectAdGroup;

final class CheckRetargetingAction
{
    public function execute(Source $source): void
    {
        $audiences = app(GetAudienceTargetsAction::class)->execute($source);

        $res = [];
        foreach ($audiences as $audience) {
            $key = (int) $audience['AdGroupId'];
            if (!isset($res[$key])) {
                $res[$key] = 0;
            }

            if (!empty($audience['RetargetingListId'])) {
                $res[$key]++;
            }
        }

        $source->settings->directAdGroupsSel
            ->each(static function (DirectAdGroup $directAdGroup) use ($res): void {
                $directAdGroup->retarget_lists_num = $res[(int) $directAdGroup->ad_group_id] ?? 0;
                $directAdGroup->save();
            });
    }
}
