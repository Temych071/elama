<?php

namespace Module\LinkChecker\Actions;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ParsePageAction
{
    /**
     * @return array{description: mixed[], keywords: mixed[], og: mixed[], title: mixed[], h_tags: mixed[], favicon: mixed[], schema: mixed[], nofollow: mixed[]}
     */
    public function execute(string $link): array
    {
        $res = Http::timeout(60)->get($link);
        $bodyHtml = $res->body();
        $dom = new Crawler($bodyHtml);
        $metaTags = self::parseMetaTags($dom);

        return [
            'description' => $this->hasDescriptions($metaTags),
            'keywords' => $this->hasKeywords($metaTags),
            'og' => $this->hasOG($metaTags),
            'title' => $this->hasTitle($dom),
            'h_tags' => $this->findHTags($dom),

            'favicon' => $this->hasFavicon($bodyHtml, $link),
            'schema' => $this->hasSchema($bodyHtml),
            'nofollow' => $this->noFollowLink($bodyHtml, parse_url($link)['host']),
        ];
    }

    private function hasTitle(Crawler $dom): array
    {
        $title = $dom->filter('title');

        if ($title->count() > 0) {
            $title = $title->first()->innerText();
        }

        if (empty($title)) {
            return [
                'title' => 'На странице не установлен заголовок, мета-тег (title)',
                'score' => 0,
                'description' => "На странице нет заголовка. Мета-тег title дает представление о содержании страницы и ее релевантности поисковому запросу. Он может использоваться в качестве заголовка в сниппете страницы.\nСодержимое тэга title: $title",
            ];
        }

        return [
            'title' => 'На странице установлен заголовок, мета-тег (title)',
            'score' => 1,
            'description' => 'На странице есть заголовок. Мета-тег title дает представление о содержании страницы и ее релевантности поисковому запросу. Он может использоваться в качестве заголовка в сниппете страницы.',
            'title_text' => $title,
        ];
    }

    /**
     * @return array{score: int|float, title: string, description: mixed[]}
     */
    private function noFollowLink(string $bodyHtml, string $host): array
    {
        $crawler = new Crawler($bodyHtml);

        $countNofollow = 0;
        $links = $crawler->filterXPath('//a')->each(function ($node) use ($host): ?string {
            $href = $node->attr('href');
            $hrefHost = parse_url($href)['host'] ?? '';
            if (!filter_var($href, FILTER_VALIDATE_URL) || str_starts_with($href, 'mailto:')) {
                return null;
            }
            if ($hrefHost === $host) {
                return null;
            }

            $status = $node->attr('rel') ?? 'follow';
            return 'Ссылка: ' . $node->attr('href') . ' . Статус: ' . $status . '; ';
        });

        $links = array_filter($links, static fn ($link): bool => !empty($link));

        foreach ($links as $link) {
            if (str_contains((string) $link, 'nofollow')) {
                $countNofollow++;
            }
        }

        $countLinks = count($links);
        $score = $countLinks <= 0 ? 0 : ($countNofollow / $countLinks) ?? 0;

        return [
            'score' => $score,
            'title' => 'Проверка внешних ссылок',
            'description' => array_values($links)
        ];
    }

    private function hasSchema(string $bodyHtml): array
    {
        $itemprop = str_contains($bodyHtml, 'itemprop');
        $itemscope = str_contains($bodyHtml, 'itemscope');
        $itemtype = str_contains($bodyHtml, 'itemtype');

        if ($itemprop || $itemscope || $itemtype) {
            return [
                'score' => 1,
                'title' => 'Микроразметка Schema'
            ];
        }
        return [
            'score' => 0,
            'title' => 'Микроразметка Schema'
        ];
    }

    /**
     * @return array{score: int, title: string, description: array<int, array{type: string, urls: mixed[], count: int}>&mixed[], totalCount: int, checkName: string, is_headers: true}
     */
    private function findHTags(Crawler $dom): array
    {
        $hTagLevelsNum = 6;

        $hTagLvls = [];
        $totalCount = 0;
        for ($hLvl = 1; $hLvl <= $hTagLevelsNum; $hLvl++) {
            $headers = $dom->filter("h$hLvl")
                ->each(static fn(Crawler $header): string => $header->outerHtml());

            $hTagLvls[] = [
                'type' => "h$hLvl",
                'urls' => $headers,
                'count' => count($headers),
            ];
            $totalCount += count($headers);
        }

        return [
            'score' => $hTagLvls[0] !== [] ? 1 : 0,
//            'score' => count($hTagLvls[0]) === 1 ? 1 : 0,
            'title' => 'Структура h1-h6',
            'description' => $hTagLvls,
            'totalCount' => $totalCount,

            'checkName' => 'imageCount', // Мега-костыль)))
            'is_headers' => true, // Мега-костыль(((
        ];
    }

    private static function getFaviconUrl(string $url): string
    {
        $uri = new Uri($url);
        return $uri->getScheme() . '://' . $uri->getHost() . '/favicon.ico';
    }

    private function hasFavicon(string $bodyHtml, string $link): array
    {
        if (
            str_contains($bodyHtml, 'type="image/x-icon"')
//            || !Http::timeout(60)->get(self::getFaviconUrl($link))->failed()
        ) {
            return [
                'title' => 'На странице присутствует фавиконка',
                'score' => 1,
            ];
        }

        return [
            'title' => 'На странице отсутствует фавиконка',
            'score' => 0,
        ];
    }

    private function hasOG(array $metaTags): array
    {
        $description = '';
        $score = 1;
        if (empty($metaTags['og:title'])) {
            $description .= PHP_EOL . 'Отсутствует заголовок, который характеризует страницу и отображается внешне.';
            $score -= .25;
        }
        if (empty($metaTags['og:type'])) {
            $description .= PHP_EOL . 'Отсутствует тип основного содержимого страницы.';
            $score -= .25;
        }
        if (empty($metaTags['og:image'])) {
            $description .= PHP_EOL . 'Отсутствует URL изображения, которое отобразится в предпоказе.';
            $score -= .25;
        }
        if (empty($metaTags['og:url'])) {
            $description .= PHP_EOL . 'Отсутствует канонический URL веб-страницы. Он используется как идентификатор объекта (веб-страницы).';
            $score -= .25;
        }

        if ($score <= 0) {
            return [
                'score' => $score,
                'title' => 'На сайте отсутствует микроразметка OG',
                'description' => $description,
            ];
        }

        return [
            'score' => $score,
            'title' => 'На сайте присутствует микроразметка OG',
            'description' => $description,
        ];
    }

    private function hasKeywords(array $metaTags): array
    {
        if (!empty($metaTags['keywords'])) {
            return [
                'score' => 1,
                'title' => 'Имеются ключевые слова',
                'description' => 'Имеются ключевые слова',
                'meta_keywords' => $metaTags['keywords'],
            ];
        }

        return [
            'score' => 0,
            'title' => 'Отсутствуют ключевые слова',
            'description' => 'Отсутствуют ключевые слова',
        ];
    }

    private function hasDescriptions(array $metaTags): array
    {
        if (!empty($metaTags['description'])) {
            return [
                'score' => 1,
                'title' => 'Присутствует описнаие страницы',
                'description' => 'Присутствует описнаие страницы',
                'meta_description' => $metaTags['description'],
            ];
        }

        return [
            'score' => 0,
            'title' => 'У страницы нет описания',
            'description' => 'У страницы нет описания',
        ];
    }

    private static function parseMetaTags(Crawler $dom): array
    {
        $metaTags = [];
        $dom->filter('meta')
            ->each(static function (Crawler $metaTag) use (&$metaTags): void {
                $key = $metaTag->attr('name') ?? $metaTag->attr('property') ?? null;
                $content = $metaTag->attr('content');
                if (empty($key) || empty($content)) {
                    return;
                }

                $metaTags[$key] = $content;
            });

        return $metaTags;
    }
}
