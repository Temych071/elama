<?php

namespace Module\Campaign\Checks\Contracts;

use Module\Campaign\Checks\DTO\CheckObject;

interface CanBeChecked
{
    public function getCheckObject(): CheckObject;
}
