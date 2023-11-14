<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\YandexDirect;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Module\Campaign\Models\Campaign;
use Module\DgMarks\Services\DgMarksGenerator;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Actions\AddYandexDirectSettingsAction;
use Module\Source\YandexDirect\Actions\Settings\GetCampaignListAction;
use Module\Source\YandexDirect\Data\CampaignData;
use Module\Source\YandexDirect\Exceptions\YandexDirectNotInitializedException;
use Module\Source\YandexDirect\Models\YandexDirectSettings;
use Throwable;

use function app;
use function redirect;

final class YandexDirectSettingsController
{
    public function show(Campaign $campaign, GetCampaignListAction $getCampaignList): Response|RedirectResponse
    {
        /** @var Source $source */
        $source = $campaign->yandexDirectSource;

        try {
            $directCampaigns = $getCampaignList->execute($campaign);
        } catch (YandexDirectNotInitializedException $e) {
            return redirect()
                ->route('campaign.source', $campaign)
                ->with('toast', [
                    'type' => 'warning',
                    'message' => $e->getMessage(),
                ]);
        }

        /** @var YandexDirectSettings|null $settings */
        $settings = $source->settings;

        return inertia('Sources/Settings/YandexDirectSettings', [
            'directCampaigns' => $directCampaigns,
            'source_type' => $source->settings_type,
            'settings' => [
                'campaigns' => is_null($settings) ? [] : $settings->campaigns,
            ],
            'dgMark' => app(DgMarksGenerator::class)
                ->gen($source),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'campaigns' => ['nullable', 'array', 'min:1'],
            'campaigns.*' => ['array:Id,Name,IsFromReport'],
            'campaigns.*.Id' => ['required', 'numeric'],
            'campaigns.*.Name' => ['required', 'string'],
            'campaigns.*.IsFromReport' => ['required', 'bool'],
        ]);

        $directCampaigns = is_null($data['campaigns']) ? null : CampaignData::collection($data['campaigns']);
        app(AddYandexDirectSettingsAction::class)->execute($campaign, $directCampaigns);

        // TODO: Редирект на проверки
        return redirect()
            ->route('campaign.browse', $campaign)
            ->with('toast', [
                'type' => 'success',
                'message' => trans('toasts.sources.settings.yandexDirect.settingsSaved'),
            ]);
    }
}
