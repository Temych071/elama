<?php

declare(strict_types=1);

namespace Module\Billing\Payments\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

final class AutoPaymentNotPaidException extends Exception
{
    #[Pure]
    public function __construct()
    {
        parent::__construct('Auto payment has not been paid.');
    }
}
