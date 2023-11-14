<?php

use App\Http\Controllers\Source\Auth\AddSourceController;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Two\User;
use Module\Source\Sources\Models\Source;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\withSession;

test('can add metrika source auth redirect', function (): void {
    \Pest\Laravel\withoutExceptionHandling();
    $user = actingAsUser();
    $campaign = $user->createCampaign('Test campaign');

    $data = [
        'campaign_id' => $campaign->id,
        'type' => Source::TYPE_YANDEX_METRIKA,
    ];

    post(route('source.add'), $data)
        ->assertSessionHasNoErrors()
        ->assertSessionHas(AddSourceController::SESSION_CAMPAIGN_KEY, $campaign->id)
        ->assertSessionHas(AddSourceController::SESSION_TYPE_KEY, Source::TYPE_YANDEX_METRIKA);
//        ->assertRedirectContains('yandex');
});

test('can add metrika source', function (): void {
    \Pest\Laravel\withoutExceptionHandling();
    mockSocialite('yandex');

    $user = actingAsUser();
    $campaign = $user->createCampaign('Test campaign');

    withSession([
        AddSourceController::SESSION_CAMPAIGN_KEY => $campaign->id,
        AddSourceController::SESSION_TYPE_KEY => Source::TYPE_YANDEX_METRIKA,
    ]);

    get(route('source.add.callback'))
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $sources = $campaign->fresh()->sources;
    expect($sources)->toHaveCount(1);
    expect($sources->first()->authToken)->token->toEqual('access-token');
    expect($sources->first()->authToken)->driver->toEqual('yandex');
    expect($sources->first())->settings_type->toEqual(Source::TYPE_YANDEX_METRIKA);
})->skip('Test is not valid'); // todo fix it

function mockSocialite(string $driver): void
{
    $abstractUser = Mockery::mock(User::class);
    $abstractUser->id = random_int(0, mt_getrandmax());
    $abstractUser->token = 'access-token';
    $abstractUser->refreshToken = 'refresh-token';
    $abstractUser->expiresIn = 1000;
    $abstractUser->name = 'user name';
    $abstractUser->nickname = 'nickname';
    $abstractUser->email = 'email@email.email';
    $abstractUser->avatar = 'https://en.gravatar.com/userimage';

    $provider = Mockery::mock(Provider::class);
    $provider->shouldReceive('user')
        ->andReturn($abstractUser);

    Socialite::shouldReceive('driver')
        ->with($driver)
        ->andReturn($provider);
}
