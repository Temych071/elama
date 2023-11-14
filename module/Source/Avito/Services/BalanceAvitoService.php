<?php

declare(strict_types=1);

namespace Module\Source\Avito\Services;

use App\Infrastructure\DateRange;
use Module\Source\Avito\Exceptions\AvitoInvalidDateRangeException;

final class BalanceAvitoService extends BaseAvitoService
{
    public function getBalance(): array
    {
        return $this->get("/core/v1/accounts/{$this->getUserId()}/balance") ?? [];
    }

    /**
     * @throws AvitoInvalidDateRangeException
     */
    public function getOperationsHistory(DateRange $dateRange): array
    {
        AvitoInvalidDateRangeException::throwIfLongerThan($dateRange, 7);
        AvitoInvalidDateRangeException::throwIfStartsBefore($dateRange, now()->subYear());

        return $this->post("/core/v1/accounts/operations_history", [
            'dateTimeFrom' => $dateRange->getFrom()->format('Y-m-d\TH:i:s'),
            'dateTimeTo' => $dateRange->getTo()->format('Y-m-d\TH:i:s'),
        ])['result']['operations'] ?? [];
    }
}
