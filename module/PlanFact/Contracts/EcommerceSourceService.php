<?php

namespace Module\PlanFact\Contracts;

use App\Infrastructure\DateRange;
use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;

interface EcommerceSourceService
{
    /**
     * @return array[]
     */
    #[ArrayShape([['date' => Carbon::class, 'income' => "int"]])]
    public function getEcommerce(DateRange $period, ?array $filters = null): array;

    public function isEcommerceEnabled(): bool;
}
