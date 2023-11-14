<?php

declare(strict_types=1);

namespace App\Crawler;

use GuzzleHttp\Exception\RequestException;
use Module\LinkChecker\Models\CrawledLink;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver;

final class CrawlSeoCheck extends CrawlObserver
{
    /**
     * @var array[]
     */
    private array $crawled = [];

    /**
     * @inheritDoc
     */
    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null): void
    {
        $this->createRow($url, $response->getStatusCode());
    }

    /**
     * @inheritDoc
     */
    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null
    ): void {
        $this->createRow($url, $requestException->getResponse()?->getStatusCode() ?? 0);
    }

    private function createRow(UriInterface $url, int $statusCode = 0): void
    {
        $data = [
            'domain' => $url->getHost(),
            'url_hash' => md5((string) $url),

            'url' => $url,
            'status_code' => $statusCode,

            'created_at' => now(),
            'updated_at' => now(),
        ];

        $affectedRows = CrawledLink::query()->upsert(
            [$data],
            ['url_hash'],
            ['status_code', 'updated_at']
        );

        if ($affectedRows !== 0) {
            $this->crawled[] = $data;
        }
    }

    public function getCrawledLinks(): array
    {
        return $this->crawled;
    }
}
