<?php

declare(strict_types=1);

namespace Loyalty\Actions;

use Loyalty\Models\Loyalty;
use Module\Campaign\Models\Campaign;

final class CreateLoyaltyAction
{
    public function execute(Campaign $project, string $name): Loyalty
    {
        /** @var Loyalty $loyalty */
        $loyalty = $project->loyalties()->create([
            'name' => $name,
            'api_token' => Loyalty::genApiToken(),
        ]);

        return $loyalty;
    }
}
