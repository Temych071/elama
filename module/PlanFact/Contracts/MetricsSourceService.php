<?php

namespace Module\PlanFact\Contracts;

use App\Infrastructure\DateRange;
use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;
use Module\Source\Sources\Models\Source;

interface MetricsSourceService
{
    public const CABINET_SOURCE_UTMS = [
        Source::TYPE_YANDEX_DIRECT => [
            [
                'operator' => 'LIKE',
                'value' => 'yandex%',
            ],
        ],
        Source::TYPE_GOOGLE_ADS => [
            [
                'operator' => 'LIKE',
                'value' => 'youtube%',
            ],
            [
                'operator' => 'LIKE',
                'value' => 'google%',
            ],
        ],
        Source::TYPE_FB => [
            [
                'operator' => 'LIKE',
                'value' => 'fb%',
            ],
            [
                'operator' => 'LIKE',
                'value' => 'facebook%',
            ],

            [
                'operator' => 'LIKE',
                'value' => 'instagram%',
            ],
        ],
        Source::TYPE_VK => [
            [
                'operator' => 'LIKE',
                'value' => 'vk%',
            ],
        ],
    ];

    public function __construct(Source $source);

    /**
     * @return array[]
     */
    #[ArrayShape([['date' => Carbon::class, 'requests' => "int"]])]
    public function getConversions(DateRange $period, ?array $filters = null): array;

    /**
     * @return string[]
     */
    public function getDomains(?DateRange $period = null, ?array $filters = null): array;

    /**
     * @return string[]
     */
    public function getDevices(?DateRange $period = null, ?array $filters = null): array;

    /**
     * @return string[]
     */
    public function getCampaignUtms(): array;

    /**
     * @return string[]
     */
    public function getSourceUtms(): array;

    /**
     * @return string[]
     */
    public function getMediumUtms(): array;
}
