<?php

declare(strict_types=1);

namespace Module\Campaign\Checks\Checks;

use Module\Campaign\Checks\Contracts\AccountCanBeChecked;

/**
 * @method getResult(AccountCanBeChecked $object);
 * @method check(AccountCanBeChecked $object): bool;
 * */
abstract class AccountCheckRule extends CheckRule
{
}
