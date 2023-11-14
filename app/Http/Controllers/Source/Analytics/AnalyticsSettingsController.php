<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\Analytics;

use App\Exceptions\BusinessException;
use Google\Service\Analytics\Goal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Module\Campaign\Models\Campaign;
use Module\Source\GoogleAnalytics\Actions\Settings\FetchAccountSummariesAction;
use Module\Source\GoogleAnalytics\Actions\Settings\FetchCounterGoalsAction;
use Module\Source\GoogleAnalytics\Actions\Settings\StoreAnalyticsSettingsAction;
use Module\Source\GoogleAnalytics\Actions\Settings\StoreAnalyticsSettingsCommand;
use Module\Source\GoogleAnalytics\Data\AnalyticsGoalData;
use Module\Source\GoogleAnalytics\Models\AnalyticsSettings;
use Module\Source\Sources\Models\Source;

final class AnalyticsSettingsController
{
    public function show(Campaign $campaign): Response
    {
        $source = $campaign->googleAnalyticsSource;

        if ($source->settings) {
            return $this->showUpdate($source);
        }

        return $this->showFirst($source);
    }

    private function showFirst(Source $source): Response
    {
        $summaries = app(FetchAccountSummariesAction::class)->execute($source);

        return Inertia::render('Sources/Settings/GoogleAnalyticsSettings', [
            'source_type' => $source->settings_type,
            'summaries' => $summaries,
        ]);
    }

    private function showUpdate(Source $source): Response
    {
        /** @var AnalyticsSettings $settings */
        $settings = $source->settings;

        /** @var Goal[] $goals */
        $goals = app(FetchCounterGoalsAction::class)->execute($source);
        $goals = array_map(static fn (Goal $item): array => (array) $item, $goals);
        $goals = AnalyticsGoalData::collection($goals);

        return Inertia::render('Sources/Settings/GoogleAnalyticsUpdateSettings', [
            'source_type' => $source->settings_type,
            'settings' => [
                'counter' => [
                    'id' => $settings->view_id,
                    'name' => $settings->view_name,
                ],
                'goals' => $settings->goals,
            ],
            'goals' => $goals,
        ]);
    }

    public function getGoals(Request $request, Campaign $campaign): array
    {
        $data = $request->validate([
            'account_id' => ['required', 'numeric'],
            'property_id' => ['required', 'string'],
            'counter_id' => ['required', 'numeric'],
        ]);

        return app(FetchCounterGoalsAction::class)->execute(
            $campaign->googleAnalyticsSource,
            (int) $data['account_id'],
            $data['property_id'],
            (int) $data['counter_id'],
        );
    }

    /**
     * @throws BusinessException
     */
    public function store(Request $request, Campaign $campaign): RedirectResponse
    {
        if ($campaign->googleAnalyticsSource->settings) {
            throw new BusinessException(trans('other.errors.source.analytics.sourceAlreadyAdded'));
        }

        $request->validate([
            'account' => 'required|array',
            'app' => 'required|array',
            'counter' => 'required|array',
            'goals' => 'required|array',
        ]);

        $command = StoreAnalyticsSettingsCommand::fromRequest($request, $campaign);

        app(StoreAnalyticsSettingsAction::class)->execute($command);

        return redirect()
            ->route('campaign.browse', $campaign);
    }

    /**
     * @throws BusinessException
     */
    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        if (!$campaign->googleAnalyticsSource->settings) {
            throw new BusinessException(trans('other.errors.source.analytics.sourceNotFound'));
        }

        $goals = $request->validate([
            'goals' => 'required|array',
        ])['goals'];

        $source = $campaign->googleAnalyticsSource;
        /** @var AnalyticsSettings $settings */
        $settings = $source->settings;
        $settings->goals = AnalyticsGoalData::collection($goals);
        $settings->save();

        return redirect()
            ->route('campaign.browse', $campaign)
            ->with('toast', [
                'type' => 'success',
                'message' => trans('toasts.sources.settings.googleAnalytics.settingsUpdated'),
            ]);
    }
}
