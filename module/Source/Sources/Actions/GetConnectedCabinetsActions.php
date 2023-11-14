<?php

declare(strict_types=1);

namespace Module\Source\Sources\Actions;

use Module\Campaign\Models\Campaign;
use Module\Source\Sources\Models\Source;

final class GetConnectedCabinetsActions
{
    private const CABINET_SOURCE_NAMES = [
        Source::TYPE_YANDEX_DIRECT,
        Source::TYPE_GOOGLE_ADS,
        Source::TYPE_VK,
        Source::TYPE_FB,
    ];

    public function execute(Campaign $campaign): array
    {
        $lst = [];

        /** @var Source $source */
        foreach ($campaign->sources as $source) {
            if (
                !is_null($source->settings_id)
                && in_array($source->settings_type, self::CABINET_SOURCE_NAMES, true)
            ) {
                $lst[] = $source->settings_type;
            }
        }

        return $lst;
    }
}
