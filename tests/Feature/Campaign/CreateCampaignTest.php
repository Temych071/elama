<?php

use Inertia\Testing\Assert;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\withoutExceptionHandling;

test('can create a new campaign', function (): void {
    withoutExceptionHandling();
    $user = actingAsUser();

    post(route('campaign.create.store'), ['name' => 'Test campaign'])
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    get(route('campaign.settings'))
        ->assertInertia(fn (Assert $page): \Inertia\Testing\Assert => $page
            ->component('Campaign/CampaignSettings')
            ->has('campaigns', 1))
        ->assertSuccessful();

    expect($user->fresh()->campaigns)->toHaveCount(1);
    expect($user->fresh()->campaigns->first())->name->toEqual('Test campaign');
    expect($user->fresh()->ownCampaigns)->toHaveCount(1);
})->skip('Test is not valid'); // todo fix it

test("a user can not view other's campaigns", function (): void {
    withoutExceptionHandling();

    \Module\User\Models\User::factory()->create()->createCampaign('test');

    $userTwo = actingAsUser();

    get(route('campaign.settings'))
        ->assertInertia(fn (Assert $page): \Inertia\Testing\Assert => $page
            ->component('Campaign/CampaignSettings')
            ->has('campaigns', 0))
        ->assertSuccessful();

//    expect($user->fresh()->campaigns)->toHaveCount(1);
//    expect($user->fresh()->campaigns->first())->name->toEqual('Test campaign');
//    expect($user->fresh()->ownCampaigns)->toHaveCount(1);
})->skip('Test is not valid'); // todo fix it
