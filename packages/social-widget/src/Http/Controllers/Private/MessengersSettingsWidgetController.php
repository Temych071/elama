<?php

declare(strict_types=1);

namespace SocialWidget\Http\Controllers\Private;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;
use Inertia\Response;
use Module\Campaign\Models\Campaign;
use Module\User\Notifications\NewSettingsRequestNotification;
use SocialWidget\Actions\GetWidgetCodeAction;
use SocialWidget\DTO\WidgetMessengersSettings;
use SocialWidget\Http\Requests\SaveWidgetMessengersRequest;
use SocialWidget\Models\SocialWidget;
use SocialWidget\Notifications\WidgetCodeNotification;

final class MessengersSettingsWidgetController
{
    public function __invoke(Campaign $project, SocialWidget $widget): Response
    {
        return Inertia::render('SocialWidget/MessengersSettings', [
            'project' => $project,
            'widgets' => $project->socialWidgets()->orderBy('created_at')->get(),
            'selectedWidget' => $widget,
            'widgetCode' => app(GetWidgetCodeAction::class)->execute($widget),
        ]);
    }

    public function save(
        SaveWidgetMessengersRequest $request,
        Campaign $project,
        SocialWidget $widget
    ): RedirectResponse {
        $data = $request->validationData();
        $data['wa_phone'] = preg_replace('/\D/', '', $data['wa_phone'] ?? '');

        $widget->messengers_settings = WidgetMessengersSettings::from($data);
        $widget->save();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Настройки мессенджеров сохранены.',
        ]);
    }

    public function sendCode(Request $request, Campaign $project, SocialWidget $widget): RedirectResponse
    {
        if (!RateLimiter::remaining('social-widget.send-widget-code.' . $widget->id, 1)) {
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Отправлять код можно только раз в минуту',
            ]);
        }

        $email = $request->validate([
            'email' => ['required', 'string', 'email'],
        ])['email'];

        Notification::route('mail', $email)
            ->notify(new WidgetCodeNotification(app(GetWidgetCodeAction::class)->execute($widget)));

        RateLimiter::hit('social-widget.send-widget-code.' . $widget->id);
        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Письмо отправлено',
        ]);
    }
}
