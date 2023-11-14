<?php

declare(strict_types=1);

namespace Module\LinkChecker\Actions;

use Illuminate\Support\Facades\Http;

final class GetPageSpeedInsightsAction
{
    public function execute(string $url): array
    {
        $result = $this->executeWithoutFilter($url);

        unset(
            $result['lighthouseResult']['audits']['screenshot-thumbnails'],
//            $result['lighthouseResult']['audits']['full-page-screenshot'],
            $result['lighthouseResult']['audits']['final-screenshot'],
            $result['lighthouseResult']['audits']['main-thread-tasks'],
        );

        $res = $result['lighthouseResult']['audits']['full-page-screenshot']['details']['screenshot']['data'] ?? null;
        if (!empty($res)) {
            $result['lighthouseResult']['audits']['full-page-screenshot'] = $res;
        }

        return $result;
    }

    public function executeWithoutFilter(string $url): array
    {
        $link = rawurlencode($url);
        $key = rawurlencode((string) config('services.google.api_key'));

        // Если формировать url с помощью массива параметров, то
        // ломается извлечение SEO и BEST_PRACTICE. Видимо из-за дублей category
//        return Cache::remember('link-checker', 8640, fn() => Http::connectTimeout(180)
//            ->timeout(180)
//            ->get(
//                "https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url=$link&category=SEO&category=BEST_PRACTICES&category=PERFORMANCE&locale=ru&key=$key"
//            )
//            ->json());
        return Http::connectTimeout(180)
            ->timeout(180)
            ->get(
                "https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url=$link&category=SEO&category=BEST_PRACTICES&category=PERFORMANCE&locale=ru&strategy=mobile&key=$key"
            )
            ->json();
    }
}
