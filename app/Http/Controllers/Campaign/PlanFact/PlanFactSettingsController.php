<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaign\PlanFact;

use App\Exceptions\ToastException;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use JetBrains\PhpStorm\ArrayShape;
use Module\Billing\Subscription\Enums\PlanFeature;
use Module\Campaign\Models\Campaign;
use Module\PlanFact\Models\PlanSettings;
use Module\PlanFact\Services\PlanFactBuilder;
use Module\Source\Sources\Actions\GetConnectedCabinetsActions;
use Throwable;

final class PlanFactSettingsController
{
    public function showAddPlan(Campaign $campaign): Response
    {
        return Inertia::render(
            'Campaign/PlanFact/AddPlan',
            array_merge(self::getDataForForm($campaign), [
                'campaign' => $campaign,
            ]),
        );
    }

    /**
     * @throws ToastException
     */
    public function addPlan(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = self::validatePlan($request, $campaign);
        $campaign->planSettings()->create($data);

        return redirect()
            ->route('campaign.project_settings', $campaign)
            ->with('toast', [
                'type' => 'success',
                'message' => trans('toasts.campaigns.planfact.settings.planCreated'),
            ]);
    }


    public function showEditPlan(Campaign $campaign, PlanSettings $planSettings): Response
    {
        return Inertia::render(
            'Campaign/PlanFact/AddPlan',
            array_merge(self::getDataForForm($campaign), [
                'campaign' => $campaign,
                'plan' => $planSettings,
            ]),
        );
    }

    /**
     * @throws ToastException
     */
    public function editPlan(Request $request, Campaign $campaign, PlanSettings $planSettings): RedirectResponse
    {
        $data = self::validatePlan($request, $campaign);

        if (!$planSettings->update($data)) {
            return back()
                ->with('toast', [
                    'type' => 'error',
                    'message' => trans('toasts.campaigns.planfact.settings.cantSavePlan'),
                ]);
        }

        return redirect()
            ->route('campaign.project_settings', $campaign)
            ->with('toast', [
                'type' => 'success',
                'message' => trans('toasts.campaigns.planfact.settings.planSaved'),
            ]);
    }

    /**
     * @throws Throwable
     */
    public function deletePlan(Campaign $campaign, PlanSettings $planSettings): RedirectResponse
    {
        $planSettings->deleteOrFail();

        return redirect()->route('campaign.project_settings', $campaign)->with('toast', [
            'type' => 'success',
            'message' => 'План удалён успешно.',
        ]);
    }

    public function updateOrder(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'numeric', 'exists:plan_settings,id'],
        ]);

        foreach ($data['order'] as $order => $index) {
            $campaign
                ->planSettings()
                ->where('id', $index)
                ->update(['order' => $order]);
        }

        return redirect()->route('campaign.project_settings', $campaign)->with('toast', [
            'type' => 'success',
            'message' => 'Порядок изменён.',
        ]);
    }

    /**
     * @return array{sources: mixed, connected_sources: mixed, domains: mixed[], campaign_names: mixed[], utm_campaigns: mixed[], utm_sources: mixed[], utm_mediums: mixed[], goals: \Module\PlanFact\DTO\PlanFactGoalData[]&\Spatie\LaravelData\DataCollection}
     */
    #[ArrayShape([
        'sources' => "mixed",
//        'devices' => "array",
        'domains' => "array",
        'campaign_names' => "array",
        'utm_campaigns' => "array",
        'utm_sources' => "array",
        'utm_medium' => "array",
        'goals' => "array",
        'connected_sources' => "array",
    ])]
    private static function getDataForForm(Campaign $campaign): array
    {
        $builder = new PlanFactBuilder($campaign);
        $builder->addAllCabinets();

        return [
            'sources' => app(GetConnectedCabinetsActions::class)->execute($campaign),
            'connected_sources' => $campaign->sources->pluck('settings_type'),
//            'devices' => $builder->getDevices(),
            'domains' => $builder->getDomains(),
            'campaign_names' => $builder->getCampaigns(),
            'utm_campaigns' => $builder->getCampaignUtms(),
            'utm_sources' => $builder->getSourceUtms(),
            'utm_mediums' => $builder->getMediumUtms(),
            'goals' => $builder->getAvailableGoals(),
        ];
    }

    /**
     * @throws ToastException
     */
    private static function validatePlan(Request $request, Campaign $campaign): array
    {
        $cabinets = app(GetConnectedCabinetsActions::class)->execute($campaign);

        $data = $request->validate([
            'name' => ['required', 'string'],
            'sources' => ['nullable', 'array'],
            'sources.*' => ['nullable', 'string', Rule::in($cabinets)],
            'utm_campaign' => ['nullable', 'array'],
            'utm_medium' => ['nullable', 'array'],
            'utm_source' => ['nullable', 'array'],
            'campaign_name' => ['nullable', 'array'],
            // TODO: Вынести куда-нить список устройств
            'device' => ['nullable', 'string', Rule::in([null, 'tablet', 'mobile', 'desktop', 'tv'])],
            'domain' => ['nullable', 'string'],
            'goals' => ['nullable', 'array'],
            'seo' => ['nullable', 'array'],
            'seo.enabled' => ['boolean'],

            'values' => ['required', 'array'],
            'values.*' => ['required', 'array'],
            'values.*.month' => ['required', 'date'],
            'values.*.expenses' => ['nullable', 'numeric', 'min:0'],
            'values.*.income' => ['nullable', 'numeric', 'min:0'],
            'values.*.requests' => ['nullable', 'numeric', 'min:0'],
            'values.*.clicks' => ['nullable', 'numeric', 'min:0'],
            'values.*.cpl' => ['nullable', 'numeric', 'min:0'],
            'values.*.cpc' => ['nullable', 'numeric', 'min:0'],
            'values.*.cr' => ['nullable', 'numeric', 'min:0'],
            'values.*.drr' => ['nullable', 'numeric', 'min:0'],
        ]);

        if (Gate::denies(PlanFeature::PLANFACT_MORE_CABINETS->value, [$campaign, $data])) {
            throw new ToastException('На текущем тарифе можно выбрать не более одного рекламного кабинета.');
        }

        foreach ($data['values'] as &$month) {
            $month['month'] = Carbon::parse($month['month']);
        }

        return $data;
    }
}
