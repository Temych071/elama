<?php

namespace Module\PlanFact\Contracts;

use App\Infrastructure\DateRange;
use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;

interface CabinetSourceService
{
    /**
     * @return array[]
     */
    #[ArrayShape([['date' => Carbon::class, 'clicks' => "int", 'expenses' => "int"]])]
    public function getStatistics(DateRange $period, array $filters): array;

    /**
     * @return string[]
     */
    public function getCampaigns(): array;
}
