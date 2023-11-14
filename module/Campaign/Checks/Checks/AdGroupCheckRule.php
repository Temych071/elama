<?php

declare(strict_types=1);

namespace Module\Campaign\Checks\Checks;

use Module\Campaign\Checks\Contracts\AdGroupCanBeChecked;

/**
 * @method getResult(AdGroupCanBeChecked $object);
 * @method check(AdGroupCanBeChecked $object): bool;
 * */
abstract class AdGroupCheckRule extends CheckRule
{
}
