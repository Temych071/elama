<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\Settings;

use App\Exceptions\BusinessException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Module\Campaign\Models\Campaign;
use Module\Source\YandexMetrika\Actions\AddMetrikaSettingsAction;
use Module\Source\YandexMetrika\Data\CounterData;
use Module\Source\YandexMetrika\Data\GoalData;
use Module\Source\YandexMetrika\Models\MetrikaSourceSettings;
use Module\Source\YandexMetrika\YandexMetrikaService;

final class MetrikaSettingsController
{
    public function show(Campaign $campaign): Response
    {
        $source = $campaign->metrikaSource;

        $service = new YandexMetrikaService($source->authToken);

        /** @var MetrikaSourceSettings|null $settings */
        $settings = $source->settings;
        $settingsCounter = is_null($settings)
            ? null
            : $service->getCounters()
                ->toCollection()
                ->first(fn (CounterData $counterData): bool => $counterData->id === $settings?->counter_id);

        return Inertia::render('Sources/Settings/MetrikaSettings', [
            'source_type' => $source->settings_type,
            'counters' => $service->getCounters(),
            'settings' => [
                'counter' => $settingsCounter,
                'goals' => $settings?->goals?->toArray() ?? [],
            ]
        ]);
    }

    /**
     * @throws BusinessException
     */
    public function store(Request $request, Campaign $campaign): RedirectResponse
    {
        $request->validate([
            'counter' => 'required|array',
            'goals' => 'required|array|min:1',
        ]);

        $counter = CounterData::from($request->input('counter'));
        $goals = GoalData::collection($request->input('goals'));

        if (
            !is_null($campaign->metrikaSource->settings)
            && $campaign->metrikaSource->settings->counter_id !== $counter->id
        ) {
            throw new BusinessException(trans('other.errors.source.metrika.cantChangeCounter'));
        }

        app(AddMetrikaSettingsAction::class)->execute($campaign, $counter, $goals);

        return redirect()
            ->route('campaign.browse', $campaign)
            ->with('toast', [
                'type' => 'success',
                'message' => trans('toasts.sources.settings.yandexMetrika.settingsSaved')
            ]);
    }
}
