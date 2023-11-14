<?php

declare(strict_types=1);

namespace Module\Source\Avito\Exceptions;

use App\Infrastructure\DateRange;
use Illuminate\Support\Carbon;
use JetBrains\PhpStorm\Pure;

final class AvitoInvalidDateRangeException extends AvitoException
{
    /**
     * @throws AvitoInvalidDateRangeException
     */
    public static function throwIfLongerThan(DateRange $dateRange, int $maxLen): void
    {
        if ($dateRange->getLength() > $maxLen) {
            throw new AvitoInvalidDateRangeException("Range must be <=$maxLen days long.");
        }
    }

    /**
     * @throws AvitoInvalidDateRangeException
     */
    public static function throwIfStartsBefore(DateRange $dateRange, Carbon $minFrom): void
    {
        if ($dateRange->getFrom()->isBefore($minFrom)) {
            throw new AvitoInvalidDateRangeException("Range must be starts after {$minFrom->format('Y-m-d')}.");
        }
    }

    #[Pure]
    public function __construct(string $message = "")
    {
        parent::__construct('Invalid date range. ' . $message);
    }
}
