<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Module\Billing\Account\Services\TransactionsService;
use Module\Campaign\Models\Campaign;
use Module\User\Models\User;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     */
    public function share(Request $request): array
    {
        
        /** @var User $user */
        $user = $request->user('web');

        print_r($user);

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user,
                'balance' => $user !== null ? app(TransactionsService::class)->balance($user) : 0,
            ],
            'campaigns' => fn (): array => $user !== null ? $this->getCampaignsData($user) : [],

            // Ломается, когда в страницу прокидывается такой же параметр или что-то типа такого.
            // Оставил, чтобы не искать сейчас везде где оно используется
            'campaign' => fn() => $request->route('campaign'),

            // Дальше лучше использовать это. Ну и не называть так пропсы страниц)
            'page_project' => fn() => $request->route('campaign')
                ?->loadMissing([
                    'activeSubscription' => static fn($q) => $q->with([
                        'plan' => static fn($q) => $q->select(['name', 'features']),
                    ])
                    ->select(['plan_id', 'id'])
                ]),

            // TODO: Выше для тарифа берутся фичи, надо будет на фронте их и использовать, а это убрать
            'features' => $request->route('campaign')?->activeSubscription?->plan?->features ?? [],

            'app' => [
                'name' => config('app.name'),
                'debug' => config('app.debug'),
                'config' => [
                    'telegram' => [
                        'bot_username' => config('telegram.bot_username'),
                    ],
                ],
            ],
            'toast' => session('toast'),
            'notifications' => $user?->notifications ?? [],
        ]);
    }

    private function getCampaignsData(User $user): array
    {
        return $user
            ->campaigns()
            ->with(['sources', 'activeSubscription' => static fn ($q) => $q->with('plan')])
            ->select(['campaigns.id', 'campaigns.name'])
            ->get()
            ->transform(fn (Campaign $campaign): array => [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'sources' => $campaign->sources->map->settings_type,
                'plan_name' => $campaign->activeSubscription?->plan?->name ?? null,
            ])
            ->toArray();
    }
}
