<?php

declare(strict_types=1);

namespace Loyalty\Exceptions;

use Error;
use Loyalty\Contracts\SmsSenderException;

final class ProstoSmsApiError extends Error implements SmsSenderException
{

}
