<?php

declare(strict_types=1);

namespace App\Http\Middleware\Source;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Module\Campaign\Models\Campaign;
use Module\Source\Sources\Models\Source;

final class CampaignHasSource
{
    public const SOURCE_NAMES = [
        Source::TYPE_YANDEX_METRIKA => 'Яндекс.Метрика',
        Source::TYPE_YANDEX_DIRECT => 'Яндекс.Директ',
        Source::TYPE_GOOGLE_ANALYTICS => 'Google Analytics',
        Source::TYPE_GOOGLE_ADS => 'Google Ads',
        Source::TYPE_FB => 'FaceBook',
        Source::TYPE_VK => 'ВКонтакте',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string  $source_type
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $source_type = 'any'): mixed
    {
        $msgTypes = null;
        /** @var Campaign $campaign */
        $campaign = $request->route('campaign');
        if (!($campaign instanceof Campaign)) {
            $campaign = Campaign::query()->findOrFail($campaign);
        }

        $q = $campaign->sources();

        if ($source_type !== 'any') {
            $types = explode('|', $source_type);
            $q->whereIn('settings_type', $types);

            $typesTitles = array_map(static fn ($type): string => self::SOURCE_NAMES[$type], $types);
            $msgTypes = implode(' или ', $typesTitles);
        }

        $sources = $q->get();
        if ($sources->isEmpty()) {
            return redirect()
                ->route('campaign.source', $campaign)
                ->with('toast', [
                    'type' => 'warning',
                    'message' => $source_type !== 'any'
                        ? 'Источник `' . $msgTypes . '` должен быть подключен'
                        : 'Должен быть подключен хотя бы один источник.'
                ]);
        }

        if (!$sources->first(static fn (Source $source): bool => $source->isReady())) {
            $firstWithoutSettings = $sources->first(static fn (Source $source): bool => !$source->isReady());
            return redirect()
                ->route('campaign.source.settings.' . $firstWithoutSettings->settings_type . '.show', $campaign)
                ->with('toast', [
                    'type' => 'warning',
                    'message' => $source_type !== 'any'
                        ? 'Источник `' . $msgTypes . '` не настроен'
                        : 'Должен быть настроен хотя бы один источник.'
                ]);
        }

        return $next($request);
    }
}
