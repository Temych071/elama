<?php

declare(strict_types=1);

namespace App\Http\Middleware\Source;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Module\Campaign\Models\Campaign;
use Module\Source\Sources\Models\Source;

final class SourceTokenValid
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
    public function handle(Request $request, Closure $next, string $source_type): mixed
    {
        /** @var Campaign $campaign */
        $campaign = $request->route('campaign');

        $types = explode('|', $source_type);
        $q = $campaign
            ->sources()
            ->whereIn('settings_type', $types);
        $msgTypes = implode(' или ', array_map(static fn ($type): string => self::SOURCE_NAMES[$type], $types));

        /** @var Source $source */
        $source = $q->first();
        if (!is_null($source) && $source->is_token_invalid) {
            return redirect()
                ->route('campaign.source', $campaign)
                ->with('toast', [
                    'type' => 'warning',
                    'message' => 'Для источника `' . $msgTypes . '` требуется обновление доступа.'
                ]);
        }

        return $next($request);
    }
}
