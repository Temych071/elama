<?php

namespace Module\LinkChecker\Actions;

use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class GetSimpleResultAction
{
    /**
     * @return array{technical_condition: array{full-page-screenshot: array{title: string, image: mixed, score: int}, status_code: mixed[], ssl_certificate: mixed, splice_www: mixed, splice_last_slash: mixed, check_404: mixed}, website_optimization: array{image_alt: mixed, robots: mixed, encoding_format: mixed, has_metrika: mixed[], has_vk_pixel: mixed[], title: mixed, keywords: mixed, page_description: mixed, crawlable-anchors: mixed, check_sitemap: mixed, count_images: mixed[], structured_data: mixed, og: mixed, favicon: mixed, h_tags: mixed, nofollow: mixed}, loading_speed: array{uses_optimized-images: mixed, unused_css_rules: mixed, server_response_time: mixed, image_size_responsive: mixed, uses_text_compression: mixed, speed_index: mixed[]}}
     */
    public function handle(array $fullResult, array $pageState): array
    {
        $simpleResult = [];
        $simpleResult['technical_condition'] = [];
        $simpleResult['website_optimization'] = [];
        $simpleResult['loading_speed'] = [];

        $networkRequests = collect(
            $fullResult['lighthouseResult']['audits']['network-requests']['details']['items'] ?? []
        );

        if (!empty($fullResult['lighthouseResult']['audits']['full-page-screenshot'] ?? null)) {
            $simpleResult['technical_condition']['full-page-screenshot'] = [
                'title' => 'Мобильная версия',
                'image' => $fullResult['lighthouseResult']['audits']['full-page-screenshot'],
                'score' => 1,
            ];
        }

        $simpleResult['technical_condition']['status_code'] = $this->getStatusCode($networkRequests);
        $simpleResult['technical_condition']['ssl_certificate'] = $fullResult['lighthouseResult']['audits']['is-on-https'] ?? ['score' => 0,];
        $simpleResult['technical_condition']['splice_www'] = $pageState['splice_www'] ?? null;
        $simpleResult['technical_condition']['splice_last_slash'] = $pageState['splice_last_slash'] ?? null;
        $simpleResult['technical_condition']['check_404'] = $pageState['check_404'] ?? null;
        $simpleResult['website_optimization']['image_alt'] =  $fullResult['lighthouseResult']['audits']['image-alt'] ?? ['score' => 0,];
        $simpleResult['website_optimization']['robots'] = $pageState['robots'];
        $simpleResult['website_optimization']['encoding_format'] =  $fullResult['lighthouseResult']['audits']['charset'] ?? ['score' => 0,];
        $simpleResult['website_optimization']['has_metrika'] = $this->hasMetrika($networkRequests);
        $simpleResult['website_optimization']['has_vk_pixel'] = $this->hasVkPixel($networkRequests);
        $simpleResult['website_optimization']['title'] =  $pageState['title'];
        $simpleResult['website_optimization']['keywords'] = $pageState['keywords'];
        $simpleResult['website_optimization']['page_description'] = $pageState['description'];
//        $simpleResult['website_optimization']['title'] =  $fullResult['lighthouseResult']['audits']['document-title'] ?? ['score' => 0,];
        $simpleResult['website_optimization']['crawlable-anchors'] =  $fullResult['lighthouseResult']['audits']['crawlable-anchors'] ?? ['score' => 0,];
        $simpleResult['website_optimization']['check_sitemap'] = $pageState['check_sitemap'] ?? null;
        $simpleResult['website_optimization']['count_images'] = $this->getImageCount($networkRequests);
        $simpleResult['website_optimization']['structured_data'] = $fullResult['lighthouseResult']['audits']['structured-data'] ?? null;
        $simpleResult['website_optimization']['og'] = $pageState['og'];
        $simpleResult['website_optimization']['favicon'] = $pageState['favicon'];
        $simpleResult['website_optimization']['h_tags'] = $pageState['h_tags'];
        $simpleResult['website_optimization']['nofollow'] = $pageState['nofollow'];
        $simpleResult['loading_speed']['uses_optimized-images'] = $fullResult['lighthouseResult']['audits']['uses-optimized-images'] ?? ['score' => 0,];
        $simpleResult['loading_speed']['unused_css_rules'] = $fullResult['lighthouseResult']['audits']['unused-css-rules'] ?? ['score' => 0,];
        $simpleResult['loading_speed']['server_response_time'] = $fullResult['lighthouseResult']['audits']['server-response-time'] ?? ['score' => 0,];
        $simpleResult['loading_speed']['image_size_responsive'] = $fullResult['lighthouseResult']['audits']['image-size-responsive'] ?? ['score' => 0,];
        $simpleResult['loading_speed']['uses_text_compression'] = $fullResult['lighthouseResult']['audits']['uses-text-compression'] ?? ['score' => 0,];
        $simpleResult['loading_speed']['speed_index'] = $this->getSpeedIndex($fullResult);

        return $simpleResult;
    }

    /**
     * @return array{title: string, description: mixed[], totalCount: int, score: int, checkName: string}
     */
    private function getImageCount(Collection $networkRequests): array
    {
        $images = $networkRequests->filter(function ($item): bool {
            if (!array_key_exists('mimeType', $item)) {
                return false;
            }
            if (array_key_exists('resourceType', $item)) {
                return str_starts_with((string) $item['mimeType'], 'image/') || $item['resourceType'] === 'Image';
            }

            return str_starts_with((string) $item['mimeType'], 'image/');
        });

        $totalCount = $images->count();
        $types = $images->map(function ($item): string {
            $mimeType = $item['mimeType'];
            return explode('/', $mimeType)[1];
        })->unique();

        $typeAndImages = $types->map(function ($type) use ($images): array {
            $items = $images->where(function ($value, $key) use ($type): bool {
                $mimeType = $value['mimeType'];
                return $type === explode('/', $mimeType)[1];
            });

            $urls = $items->map(fn ($item) => $item['url'] ?? '');

            return [
                'type' => $type,
                'urls' => array_values($urls->toArray()),
                'count' => $urls->count(),
            ];
        });

        return [
            'title' => 'Количество изображений по форматам',
            'description' => array_values($typeAndImages->toArray()),
            'totalCount' => $totalCount,
            'score' => 1,
            'checkName' => 'imageCount',
        ];
    }

    private function getSpeedIndex(array $fullResult): array
    {
        try {
            $speedIndex = $fullResult['lighthouseResult']['audits']['speed-index'];
            if ($speedIndex['numericValue'] <= 3.4) {
                $status = 'fast';
            } elseif ($speedIndex['numericValue'] > 3.4 && $speedIndex['numericValue'] <= 5.8) {
                $status = 'middle';
            } elseif ($speedIndex['numericValue'] > 5.8) {
                $status = 'slow';
            }

            return [
                'title' => 'Скорость загрузки составляет ' . $speedIndex['displayValue'],
                'score' => $speedIndex['score'],
                'description' => $speedIndex['description'],
                'status' => $status ?? 'slow',
            ];
        } catch (\Exception) {
            return [
                'score' => 0,
                'title' => 'Неудалось получить данные о скорости загрузки'
            ];
        }
    }

    #[ArrayShape(['score' => "int", 'title' => "string"])]
    private function hasMetrika(Collection $networkRequests): array
    {
        $metrika = $networkRequests->first(
            fn ($it): bool => str_contains(mb_strtolower($it['url'] ?? ''), 'metrika')
        ) !== null;
        if ($metrika) {
            return [
                'score' => 1,
                'title' => 'На странице установлен счетчик Яндекс.Метрики'
            ];
        }
        return [
            'score' => 0,
            'title' => 'На странице не установлен счетчик Яндекс.Метрики'
        ];
    }

    #[ArrayShape(['score' => "int", 'title' => "string"])]
    private function hasVkPixel(Collection $networkRequests): array
    {
        $vk = $networkRequests->first(
            fn ($it): bool => str_contains(mb_strtolower($it['url'] ?? ''), 'vk-rtrg')
        ) !== null;
        if ($vk) {
            return [
                'score' => 1,
                'title' => 'На странице установлен пиксель ВКонтакте'
            ];
        }
        return [
            'score' => 0,
            'title' => 'На странице не установлен пиксель ВКонтакте'
        ];
    }

    /**
     * @return array{code: mixed, title: string, score: int}
     */
    #[ArrayShape(['code' => "int|mixed", 'title' => "string", 'score' => "int"])]
    private function getStatusCode(Collection $networkRequests): array
    {
        $code = $networkRequests->first(fn ($it): bool => ($it['resourceType'] ?? null) === 'Document')['statusCode'] ?? 0;
        return [
            'code' => $code,
            'title' => 'Код ответа страницы: '.$code,
            'score' => (int)$code >= 400 ? 0 : 1,
        ];
    }
}
