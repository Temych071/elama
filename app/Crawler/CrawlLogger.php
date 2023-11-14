<?php

declare(strict_types=1);

namespace App\Crawler;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver;

final class CrawlLogger extends CrawlObserver
{
    public function __construct(
        private readonly Command $console
    ) {
    }

    public function willCrawl(UriInterface $url): void
    {
        $this->console->comment("Found: $url");
    }

    /**
     * @inheritDoc
     */
    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null): void
    {
        $this->console->comment("[{$response->getStatusCode()}] $url");
    }

    /**
     * @inheritDoc
     */
    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null
    ): void {
        $this->console->comment("[{$requestException->getResponse()?->getStatusCode()}] $url");
    }
}
