<?php

declare(strict_types=1);

namespace Loyalty\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

final class LoyaltyClassNotExistsException extends Exception
{
    #[Pure]
    public function __construct()
    {
        parent::__construct('Класс карт лояльности ещё не был создан в GoogleWallet.');
    }
}
