<?php

use Module\User\Models\User;

use function Pest\Laravel\get;

test('user cannot see others campaigns', function (): void {
    $campaign = User::factory()->create()->createCampaign('test');

    actingAsUser();

    get(route('campaign.source', $campaign))
        ->assertStatus(302);
});
