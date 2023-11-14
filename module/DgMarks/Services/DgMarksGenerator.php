<?php

declare(strict_types=1);

namespace Module\DgMarks\Services;

use Module\DgMarks\Enums\DgMarkSource;
use Module\Source\Sources\Models\Source;

final class DgMarksGenerator
{
    public function gen(Source $source): ?string
    {
        return match ($source->settings_type) {
            Source::TYPE_YANDEX_DIRECT => self::yandexDirect($source),
            Source::TYPE_VK => self::vk($source),
            default => null,
        };
    }

    private static function yandexDirect(Source $source): string
    {
        // ?dg=direct<source_id>_<campaign_id>_<ad_group_id>_<ad_id>_<keyword_id>
        // https://yandex.ru/support/direct/statistics/url-tags.html#url-tags__dynamic
        // ?dg=direct{$source->id}_{campaign_id}_{gbid}_{ad_id}_{phrase_id}

        return DgMarkSource::YANDEX_DIRECT->value . "_{$source->id}_{campaign_id}_{gbid}_{ad_id}_{phrase_id}";
    }

    private static function vk(Source $source): string
    {
        // site.link/path?dg=vk<source_id>_<campaign_id>_<ad_id>_<keyword>
        // https://vk.com/faq11846
        // site.link/path?dg=vk{$source->id}_{campaign_id}_{ad_id}_{keyword}

        return DgMarkSource::VK->value . "_{$source->id}_{campaign_id}_{ad_id}_{keyword}";
    }
}
