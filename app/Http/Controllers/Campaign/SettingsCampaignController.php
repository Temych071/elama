<?php

namespace App\Http\Controllers\Campaign;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Module\Campaign\DTO\ProjectSettingsChecks;
use Module\Campaign\Models\Campaign;

final class SettingsCampaignController
{
    public function show(Campaign $campaign): Responsable
    {
        $plans = $campaign->planSettings()
            ->get();

        $ym = $campaign->metrikaSource;
        $ga = $campaign->googleAnalyticsSource;

        return Inertia::render('Campaign/ProjectSettings', [
            'campaign' => $campaign->append('planfact_order'),
            'plans' => $plans,
            'notificationTypes' => $this->getNotificationTypes(),
            'emptyAnalyticsSources' => empty($ym) && empty($ga),
            'settingsChecks' => $campaign->settings_checks,
        ]);
    }

    public function storeChecks(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'showLinkChecker' => ['boolean'],
            'showSeoAudit' => ['boolean'],
        ]);

        $campaign->settings_checks = ProjectSettingsChecks::from(
            array_merge($campaign->settings_checks->toArray() ?? [], $data)
        );
        $campaign->save();

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Настройка сохранена.',
        ]);
    }

    public function storeAnalytics(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'order' => ['present', 'array'],
            'order.*' => ['required', 'string'],
        ]);

        $campaign->update(['analytics_parameters' => $data['order']]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Показатели аналитики сохранены.',
        ]);
    }

    public function storeCampaignNotifications(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'value' => ['present', 'array'],
            'value.*' => ['required', 'string'],
        ]);


        $campaign->update(['notifications' => $data['value']]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Уведомления сохранены.',
        ]);
    }

    /**
     * @return array{new_users: string, avg_visit_duration: string, page_views: string, bounce_rate: string, depth: string, reaches: string, conversion_rate: string, mobile_traffic: string, city_reaches: string, city_new_users: string, audit: string, visitors: string, purchases: string, income: string}
     */
    private function getNotificationTypes(): array
    {
        return [
            'new_users' => 'Новые пользователи',
            'avg_visit_duration' => 'Время на сайте',
            'page_views' => 'Визиты',
            'bounce_rate' => 'Отказы',
            'depth' => 'Глубина',
            'reaches' => 'Заявки',
            'conversion_rate' => 'Конверсия',
            'mobile_traffic' => 'Мобильный трафик',
            'city_reaches' => 'Регионы по заявкам',
            'city_new_users' => 'Регионы по новым пользователям',
            'audit' => 'Аудит',
            'visitors' => 'Посетители',
            'purchases' => 'Покупки',
            'income' => 'Доход',
        ];
    }
}
