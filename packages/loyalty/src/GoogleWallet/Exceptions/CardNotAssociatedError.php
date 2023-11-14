<?php

declare(strict_types=1);

namespace Loyalty\GoogleWallet\Exceptions;

use JetBrains\PhpStorm\Pure;

final class CardNotAssociatedError extends \Error
{
    #[Pure] public function __construct()
    {
        parent::__construct('Карта лояльности не привязана к клиенту.');
    }

}
