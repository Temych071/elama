<?php

namespace Module\Campaign\Checks\Checks;

use Module\Campaign\Checks\Contracts\CampaignCanBeChecked;

/**
 * @method getResult(CampaignCanBeChecked $object);
 * @method check(CampaignCanBeChecked $object);
 * */
abstract class CampaignCheckRule extends CheckRule
{
}
