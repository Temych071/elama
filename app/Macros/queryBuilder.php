<?php

use App\Infrastructure\DateRange;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as BaseBuilder;

BaseBuilder::macro(
    'whereInDateRange',
    fn(DateRange $dareRange, string $col = 'date') => /* @var BaseBuilder $this */
$this
        ->whereDate($col, '>=', $dareRange->getFrom())
        ->whereDate($col, '<=', $dareRange->getTo())
);

BaseBuilder::macro(
    'whereOutDateRange',
    fn(DateRange $dareRange, string $col = 'date') => /* @var BaseBuilder $this */
$this
        ->whereDate($col, '<', $dareRange->getFrom())
        ->whereDate($col, '>', $dareRange->getTo())
);

EloquentBuilder::macro(
    'whereInDateRange',
    fn(DateRange $dareRange, string $col = 'date') => /* @var EloquentBuilder $this */
$this
        ->whereDate($col, '>=', $dareRange->getFrom())
        ->whereDate($col, '<=', $dareRange->getTo())
);

EloquentBuilder::macro(
    'whereOutDateRange',
    fn(DateRange $dareRange, string $col = 'date') => /* @var EloquentBuilder $this */
$this
        ->whereDate($col, '<', $dareRange->getFrom())
        ->whereDate($col, '>', $dareRange->getTo())
);
