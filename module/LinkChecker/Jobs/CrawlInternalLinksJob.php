<?php

declare(strict_types=1);

namespace Module\LinkChecker\Jobs;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Module\LinkChecker\Actions\CrawlInternalLinksAction;
use Module\LinkChecker\Models\CrawledLink;
use Module\LinkChecker\Models\SeoAudit;

final class CrawlInternalLinksJob implements ShouldQueue, ShouldBeUnique
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $uniqueFor = 216_000; // 1 hour

    public function __construct(
        public readonly string $url,
    ) {
        $this->onQueue('seo');
    }

    public function handle(): void
    {
        if (CrawledLink::getTotalLinksCountForUrl($this->url) !== 0) {
            return;
        }

        $crawledLinks = collect(app(CrawlInternalLinksAction::class)->execute($this->url));

        $failed = $crawledLinks->filter(
            static fn (array $item): bool => (
                $item['status_code'] < 200
                || $item['status_code'] >= 300
            )
        );

        if ($failed->count() === 0) {
            return;
        }

        $domain = (new Uri($this->url))->getHost();

        SeoAudit::query()
            ->where('link', 'LIKE', "%$domain%")
            ->update([
                'internal_links' => json_encode([
                    'title' => 'Проверка битых ссылок (внутренних)',
                    'description' => 'Некоторые ссылки на сайте не работают.',
                    'score' => 0,
                    'details' => [
                        'type' => 'table',
                        'headings' => [
                            [
                                'text' => 'URL',
                                'key' => 'url',
                                'itemType' => 'url',
                            ],
                            [
                                'text' => 'Статус ответа',
                                'key' => 'status_code',
                                'itemType' => 'text',
                            ],
                        ],
                        'items' => $failed
                            ->map(static fn (array $link): array => ['url' => $link['url'], 'status_code' => $link['status_code']])
                            ->toArray(),
                    ],
                ])
            ]);
    }

    public function uniqueId(): string
    {
        return $this->url;
    }
}
