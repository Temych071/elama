<?php

declare(strict_types=1);

namespace Module\Campaign\Checks\Checks;

use Module\Campaign\Checks\Contracts\AdCanBeChecked;

/**
 * @method getResult(AdCanBeChecked $object);
 * @method check(AdCanBeChecked $object): bool;
 * */
abstract class AdCheckRule extends CheckRule
{
}
