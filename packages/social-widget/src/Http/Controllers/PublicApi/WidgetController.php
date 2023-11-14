<?php

declare(strict_types=1);

namespace SocialWidget\Http\Controllers\PublicApi;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Module\Billing\Subscription\Enums\SubscriptionStatus;
use SocialWidget\Actions\GetCrmServiceByWidgetAction;
use SocialWidget\Crm\CreateLeadCommand;
use SocialWidget\Models\SocialWidget;
use SocialWidget\Services\TrackWidgetStatsService;
use Throwable;

final class WidgetController
{

    public function redirect(Request $request, string $uuid): RedirectResponse
    {
        /** @var SocialWidget $widget */
        $widget = SocialWidget::query()->findOrFail($uuid);

        try {
            $data = $request->validate([
                'messenger' => ['required', Rule::in($widget->messengers_list)],
                'current_url' => ['required', 'url'],
                'referer' => ['nullable', 'url'],
                'device' => ['nullable', Rule::in(['desktop', 'tablet', 'mobile'])],
            ]);
        } catch (ValidationException) {
            abort(400, "Invalid request.");
        }

        $url = match ($data['messenger']) {
            'tg' => 'https://t.me/'.Str::after($widget->messengers_settings->tg_nickname, 't.me/'),
            'wa' => sprintf(
                'https://%s.whatsapp.com/send/?phone=%s&type=phone_number&app_absent=0&text=%s',
                match ($data['device']) {
                    'tablet', 'mobile' => 'api',
                    'desktop' => $widget->messengers_settings->wa_redirect_type->value,
//                    'desktop' => 'web',
                },
                $widget->messengers_settings->wa_phone,
                str_replace("\n", ' ', $widget->messengers_settings->wa_message ?? ''),
            ),
        };

        $leadIndex = null;
        if ($data['messenger'] !== 'tg' || !$widget->messengers_settings->tg_dont_create_leads) {
            parse_str(parse_url($data['current_url'] ?? null)['query'] ?? '', $params);
            $createLeadCommand = new CreateLeadCommand(
                title: "DailyGrow | Виджет",
                utmSource: $params['utm_source'] ?? null,
                utmMedium: $params['utm_medium'] ?? null,
                utmCampaign: $params['utm_campaign'] ?? null,
                referer: $data['referer'] ?? $data['current_url'] ?? config('app.url'),
                form_page: $data['current_url'] ?? config('app.url'),
                ip: $request->ip() ?? '0.0.0.0',
            );

            $crmService = app(GetCrmServiceByWidgetAction::class)->execute($widget);
            try {
                $leadIndex = $crmService?->createLead($createLeadCommand);
            } catch (Throwable $throwable) {
                /** @noinspection NullPointerExceptionInspection */
                $crmService->disableIntegration();
                Log::error('Exception during crm lead creation.', [
                    'code' => $throwable->getCode(),
                    'message' => $throwable->getMessage(),
                    'trace' => $throwable->getTraceAsString(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ]);
            }
        }

        if ($leadIndex !== null) {
            $url = str_replace('{client-id}', $leadIndex, $url);
        } else {
            $url = str_replace('{client-id}', '-', $url);
        }

        if (!Str::startsWith($url, 'https://')) {
            $url = "https://$url";
        }

        /** @var SocialWidget $widget */
        $widget = SocialWidget::query()->findOrFail($uuid);
        app(TrackWidgetStatsService::class)->incClicks($widget);

        return redirect($url);
    }

    private function canDisableCopyright(SocialWidget $widget): bool
    {
        return $widget->project->activeSubscription?->status === SubscriptionStatus::active;
    }

    public function load(string $uuid): array
    {
        /** @var SocialWidget $widget */
        $widget = SocialWidget::query()->findOrFail($uuid);

        $widgetData = $widget->only([
            'id',
            'view_settings',
            'messengers_list',
            'stats_counters',
        ]);
        // Выглядит странно)
        $widgetData['view_settings']->disable_copyright = $widgetData['view_settings']->disable_copyright && $this->canDisableCopyright($widget);

        return $widgetData;
    }

    /**
     * @return array{import: array{js: UrlGenerator[]|string[], css: UrlGenerator[]|string[]}}
     */
    public function assets(): array
    {
        return [
            'import' => [
                'js' => [
                    url('social-widget/social-widget.v6.js'),
                ],
                'css' => [
                    url('social-widget/social-widget.v6.css'),
                ],
            ],
        ];
    }
}
