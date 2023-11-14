<?php

declare(strict_types=1);

namespace SocialWidget\Http\Controllers\Private;

use App\Exceptions\ToastException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Module\Campaign\Models\Campaign;
use SocialWidget\Crm\Impl\AmoService;
use SocialWidget\DTO\WidgetCrmIntegrationsSettings;
use SocialWidget\DTO\WidgetMessengersSettings;
use SocialWidget\DTO\WidgetStatsIntegrationsSettings;
use SocialWidget\Http\Requests\SaveWidgetCrmIntegrationsRequest;
use SocialWidget\Http\Requests\SaveWidgetMessengersRequest;
use SocialWidget\Http\Requests\SaveWidgetStatsIntegrationsRequest;
use SocialWidget\Models\SocialWidget;

final class IntegrationsSettingsWidgetController
{
    public function __invoke(Campaign $project, SocialWidget $widget): Response
    {
        return Inertia::render('SocialWidget/IntegrationsSettings', [
            'project' => $project,
            'widgets' => $project->socialWidgets()->orderBy('created_at')->get(),
            'selectedWidget' => $widget,
        ]);
    }

    public function saveStats(
        SaveWidgetStatsIntegrationsRequest $request,
        Campaign $project,
        SocialWidget $widget
    ): RedirectResponse {
        $widget->stats_integrations_settings = WidgetStatsIntegrationsSettings::from($request->validationData());
        $widget->save();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Настройки интеграций сохранены.',
        ]);
    }

    public function saveCrm(
        SaveWidgetCrmIntegrationsRequest $request,
        Campaign $project,
        SocialWidget $widget
    ): RedirectResponse {
        $settings = WidgetCrmIntegrationsSettings::from($request->validationData());
        if ($settings->amo_enabled && $settings->bx_enabled) {
            throw new ToastException('Одновременно может быть подключена только одна CRM.');
        }

        if ($settings->amo_enabled && ($widget->amo_access_token === null || $widget->amo_domain === null)) {
            throw new ToastException('Перед включением amoCrm подключите аккаунт.');
        }

        $widget->crm_integrations_settings = $settings;
        $widget->save();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Настройки интеграций сохранены.',
        ]);
    }

    public function amoAuthRedirect(Campaign $project, SocialWidget $widget): RedirectResponse
    {
        return redirect()->to(AmoService::getAuthUrl($widget->id));
    }
}
