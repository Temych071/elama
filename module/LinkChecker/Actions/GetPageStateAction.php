<?php

declare(strict_types=1);

namespace Module\LinkChecker\Actions;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

final class GetPageStateAction
{
    public function execute(string $link): array
    {
        $robots = self::getRobots($link);

        return [
            'splice_www' => $this->checkSpliceWWW($link),
            'splice_last_slash' => $this->spliceLastSlash($link),
            'check_404' => $this->check404($link),
            'check_sitemap' => $this->checkSitemap($robots),
            'robots' => $this->checkRobots($robots),
            ...app(ParsePageAction::class)->execute($link)
        ];
    }

    private static function getRobotsUrl(string $url): string
    {
        // https://developers.google.com/search/docs/advanced/robots/robots_txt?hl=ru#examples-of-valid-robots.txt-urls

        $uri = new Uri($url);
        return $uri->getScheme() . '://' . $uri->getHost() . '/robots.txt';
    }

    private static function getRobots(string $url): ?array
    {
        $robotsUrl = self::getRobotsUrl($url);
        $res = Http::get($robotsUrl);

        if ($res->failed()) {
            return null;
        }

        return [
            'url' => $robotsUrl,
            'content' => $res->body(),
        ];
    }

    private function checkRobots(?array $robots): array
    {
        if (is_null($robots)) {
            return [
                'score' => 0,
                'title' => 'Отсутствует robots.txt',
            ];
        }

        return [
            'score' => 1,
            'title' => 'Присутствует robots.txt',
            'robots_url' => $robots['url'],
        ];
    }

    private function checkSitemap(?array $robots): array
    {
        // https://developers.google.com/search/docs/advanced/robots/robots_txt?hl=ru#sitemap

        if (is_null($robots)) {
            return [
                'score' => 0,
                'title' => 'Отсутствует sitemap.xml',
            ];
        }

        preg_match_all('/Sitemap:\s*([^\r\n]+)/i', (string) $robots['content'], $m);
        $sitemapUrls = $m[1] ?? [];

        if (!empty($sitemapUrls)) {
            $sitemapUrls = array_map(
                static fn ($sitemapUrl) => "<a target='_blank' class='link' href='$sitemapUrl'>$sitemapUrl</a>",
                $sitemapUrls
            );
            $sitemapUrls = implode('<br>', $sitemapUrls);

            return [
                'score' => 1,
                'title' => 'Присутствует sitemap.xml',
                'sitemap_urls' => $sitemapUrls,
            ];
        }

        return [
            'score' => 0,
            'title' => 'Отсутствует sitemap.xml'
        ];
    }

    private function check404(string $link): array
    {
        $link = rtrim($link, '/') . '/';
        $uniqString = uniqid('', false);

        $responses = Http::pool(static fn (Pool $pool): array => [
            $pool->get($link . $uniqString),
            $pool->get($link . $uniqString . '.php'),
            $pool->get($link . $uniqString . '.html')
        ]);

        $firstLink = $responses[0]->status() === 404;
        $secondLink = $responses[1]->status() === 404;
        $thirdLink = $responses[2]->status() === 404;

        if ($firstLink && $secondLink && $thirdLink) {
            return [
                'score' => 1,
                'title' => 'Присутствует страница 404'
            ];
        }
        return [
            'score' => 0,
            'title' => 'Отсутствует страница 404'
        ];
    }

    private function checkSpliceWww(string $url): array
    {
        $redirectHistory = $this->getRedirectHistory(self::getWwwUrl($url));

        if (
            empty($redirectHistory)
            || Str::startsWith(Arr::last($redirectHistory), 'www.')
        ) {
            return [
                'title' => 'Отсутсвует склейка www',
                'score' => 0,
            ];
        }

        return [
            'title' => 'Есть склейка www',
            'score' => 1,
        ];
    }

    private static function getWwwUrl(string $url): string
    {
        $uri = new Uri($url);
        return $uri->getScheme() . '://' . Str::start($uri->getHost(), 'www.');
    }

    private function spliceLastSlash(string $url): array
    {
        return $this->checkSlashes($url) ? [
            'score' => 1,
            'title' => "Присутсувет склейка последнего '/'",
            'description' => 'url должен заканчиваться на /, например test.ru/page/'
        ] : [
            'score' => 0,
            'title' => "Отсутствует склейка последнего '/'",
            'description' => 'url должен заканчиваться на /, например test.ru/page/'
        ];
    }

    private function checkSlashes(string $url): bool
    {
        $trimmedUrl = self::getTrimmedUrl($url);
//        $isRoot = self::isRootUrl($trimmedUrl);

//        if ($isRoot) {
//            return !$this->isSlashExists($trimmedUrl . '/');
//        }

//        $rootUrl = self::getRootUrl($trimmedUrl);

        return (
            $this->isSlashExists($trimmedUrl . '/') === $this->isSlashExists($trimmedUrl)
//            && !$this->isSlashExists($rootUrl . '/')
        );
    }

    private function isSlashExists(string $url): bool
    {
        $redirectHistory = $this->getRedirectHistory($url);

        if (empty($redirectHistory)) {
            return false;
        }

        $path = (new Uri(Arr::last($redirectHistory)))->getPath();
        return (bool)preg_match('/[^\/]\/$/', $path);
    }

    private static function getTrimmedUrl(string $url): string
    {
        // Или лучше через ->with...('') менять урл?
        $uri = new Uri($url);
        return $uri->getScheme() . '://' . $uri->getHost() . rtrim($uri->getPath(), '/');
    }

    private static function getRootUrl(string $url): string
    {
        $uri = new Uri($url);
        return $uri->getScheme() . '://' . $uri->getHost();
    }

    private function getRedirectHistory(string $url): ?array
    {
        try {
            $res = Http::withOptions([
                'allow_redirects' => [
                    'track_redirects' => true
                ]
            ])
                ->timeout(60)
                ->get($url);
        } catch (Throwable) {
            return null;
        }

        if ($res->failed()) {
            return null;
        }

        $header = $res->header('X-Guzzle-Redirect-History');
        $history = explode(', ', $header);

        return array_filter([$url, ...$history], static fn ($item): bool => !empty($item));
    }

    private static function isRootUrl(string $url): bool
    {
        return empty(trim((new Uri($url))->getPath(), '/'));
    }
}
