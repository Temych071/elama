<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Actions;

use Module\DgMarks\Enums\DgMarkSource;
use Module\Source\Analytics\DTO\CabinetsFilter;
use Module\Source\Sources\Models\Source;

final class CabinetFilterToDgMarks
{
    /**
     * @return array<string, string[]|float[]|int[]|string>
     * @noinspection PhpUndefinedClassInspection
     */
    public function execute(?CabinetsFilter $cabinetsFilter = null): array
    {
        if ($cabinetsFilter === null) {
            return [];
        }

        $res = [];

        if (!empty($cabinetsFilter->ad_ids)) {
            $key = match ($cabinetsFilter->source_type) {
                Source::TYPE_VK => 'dg_3',
                Source::TYPE_YANDEX_DIRECT => 'dg_4',
                default => false,
            };

            if ($key) {
                $res[$key] = $cabinetsFilter->ad_ids;
            }
        } elseif (!empty($cabinetsFilter->group_ids)) {
            $res['dg_3'] = $cabinetsFilter->group_ids;
        } elseif (!empty($cabinetsFilter->source_type)) {
            $res['dg_source'] = match ($cabinetsFilter->source_type) {
                Source::TYPE_VK => DgMarkSource::VK->value,
                Source::TYPE_YANDEX_DIRECT => DgMarkSource::YANDEX_DIRECT->value,
                default => false,
            };

            if ($res['dg_source'] === false) {
                unset($res['dg_source']);
            }
        } elseif (!empty($cabinetsFilter->campaign_ids)) {
            $res['dg_2'] = $cabinetsFilter->campaign_ids;
        } elseif (!empty($cabinetsFilter->account_ids)) {
            $res['dg_1'] = Source::query()
                ->where('settings_type', $cabinetsFilter->source_type)
                ->whereIn('settings_id', $cabinetsFilter->account_ids)
                ->get()
                ->pluck('id')
                ->toArray();
        }

        return $res;
    }
}
