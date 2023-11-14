<?php

declare(strict_types=1);

namespace SocialWidget\Http\Controllers\Private;

use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Module\Billing\Subscription\Enums\SubscriptionStatus;
use Module\Campaign\Models\Campaign;
use SocialWidget\DTO\WidgetViewSettings;
use SocialWidget\Http\Requests\SaveWidgetViewRequest;
use SocialWidget\Models\SocialWidget;

final class ViewSettingsWidgetController
{
    public function __invoke(Campaign $project, SocialWidget $widget): Response
    {
        return Inertia::render('SocialWidget/ViewSettings', [
            'project' => $project,
            'widgets' => $project->socialWidgets()->orderBy('created_at')->get(),
            'selectedWidget' => $widget,
            'canDisableCopyright' => $this->canDisableCopyright($project),
        ]);
    }

    public function save(SaveWidgetViewRequest $request, Campaign $project, SocialWidget $widget): RedirectResponse
    {
        $data = $request->validationData();

        // Всё равно не будет работать
//        if ($data['disable_copyright'] && !$this->canDisableCopyright($project)) {
//            throw new ToastException('Скрытие логотипа доступно только на платном тарифе');
//        }

        if (!empty($path = $widget->loadMedia($request->file('avatar')))) {
            $data['avatar_path'] = $path;
            $widget->removeMedia($widget->view_settings->avatar_path);
        }

        $widget->view_settings = WidgetViewSettings::from($data);
        $widget->save();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Настройки дизайна виджета сохранены.',
        ]);
    }

    private function canDisableCopyright(Campaign $project): bool
    {
        return $project->activeSubscription?->status === SubscriptionStatus::active;
    }
}
