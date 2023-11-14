<?php

declare(strict_types=1);

namespace Module\Source\Sources\Collections;

use App\Infrastructure\DateRange;
use Illuminate\Support\Collection;

final class SourceDataCollection extends Collection
{
    public function getDays(DateRange $dateRange): self
    {
        return $this->only($dateRange->getDaysWithFormat());
    }

//    public function groupByDays(int $daysNum): self
//    {
//
//    }
    // Перегруппировка
    // И т.п.
}
