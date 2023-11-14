<?php

declare(strict_types=1);

namespace Module\LinkChecker\Models;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

final class CrawledLink extends Model
{
    protected $table = 'seo_crawled_links';
    protected $keyType = 'string';
    protected $primaryKey = 'url_hash';
    public $incrementing = false;

    public const CHECK_ACTUAL_DAYS = 30;

    public function scopeActual(Builder $q): Builder
    {
        return $q->whereDate('updated_at', '>=', now()->subDays(self::CHECK_ACTUAL_DAYS));
    }

    public function scopeDomain(Builder $q, string $domain): Builder
    {
        return $q->where('domain', $domain);
    }

    public function scopeUrl(Builder $q, string $url): Builder
    {
        return $q->where('url_hash', md5($url));
    }

    public function scopeForUrl(Builder $q, string $url): Builder
    {
        return $q->domain((new Uri($url))->getHost());
    }

    public function scopeFailed(Builder $q): Builder
    {
        return $q->where(
            static fn (Builder $q): \Illuminate\Database\Eloquent\Builder => $q
                ->orWhere('status_code', '<', 200)
                ->orWhere('status_code', '>=', 300)
        );
    }

    public static function getFailedLinksForUrl(string $url): Collection
    {
        return self::query()
            ->actual()
            ->forUrl($url)
            ->failed()
            ->select(['url', 'status_code'])
            ->get();
    }

    public static function getTotalLinksCountForUrl(string $url): int
    {
        return self::query()
            ->actual()
            ->forUrl($url)
            ->count();
    }
}
