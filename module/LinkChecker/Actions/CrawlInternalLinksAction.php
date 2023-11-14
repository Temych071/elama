<?php

declare(strict_types=1);

namespace Module\LinkChecker\Actions;

use App\Crawler\CrawlSeoCheck;
use GuzzleHttp\RequestOptions;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlProfiles\CrawlInternalUrls;

final class CrawlInternalLinksAction
{
    public function execute(string $url): array
    {
        $observer = new CrawlSeoCheck();

        Crawler::create([
            RequestOptions::ALLOW_REDIRECTS => true,
            RequestOptions::HEADERS => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36'
            ]
        ])
            ->setMaximumDepth(3)
            ->setTotalCrawlLimit(500)
            ->setDelayBetweenRequests(1000)
            ->setCrawlProfile(new CrawlInternalUrls($url))
            ->addCrawlObserver($observer)
            ->startCrawling($url);

        return $observer->getCrawledLinks();
    }
}
