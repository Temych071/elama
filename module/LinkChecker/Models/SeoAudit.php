<?php

declare(strict_types=1);

namespace Module\LinkChecker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Module\LinkChecker\Actions\GetSimpleResultAction;

final class SeoAudit extends Model
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_ERROR = 'error';
    public const STATUS_WAIT = 'wait';

    protected $table = 'seo_audits';

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'link',
        'result',
        'status',
        'data_updated_at',
        'performance_score',
        'seo_score',
        'best_practices_score',
        'document_status_code',
        'has_metrika',
        'has_vk_pixel',
        'simple_result'
    ];

    protected $casts = [
        'result' => 'json',
        'simple_result' => 'json',
        'internal_links' => 'json',
        'data_updated_at' => 'datetime',
    ];

    public static function findOrCreateByLink(string $link): SeoAudit
    {
        if (($model = self::findByLink($link)) !== null) {
            return $model;
        }

        return self::createWithLink($link);
    }

    public static function findByLink(string $link): ?SeoAudit
    {
        return self::query()->where('link', $link)->first();
    }

    public static function createWithLink(string $link): self
    {
        return self::query()->forceCreate([
            'uuid' => Str::uuid()->toString(),
            'link' => $link,
            'status' => self::STATUS_WAIT,
        ]);
    }

    public function saveError(): bool
    {
        $this->status = self::STATUS_ERROR;
        return $this->update();
    }

    public function saveResult(array $result, ?array $pageState = null): bool
    {
        $action = new GetSimpleResultAction();

        $this->result = [];
        $this->status = self::STATUS_SUCCESS;
        $this->data_updated_at = Carbon::now();

        $this->performance_score = $result['lighthouseResult']['categories']['performance']['score'] ?? null;
        $this->best_practices_score = $result['lighthouseResult']['categories']['best-practices']['score'] ?? null;
        $this->seo_score = $result['lighthouseResult']['categories']['seo']['score'] ?? null;

        $networkRequests = collect($result['lighthouseResult']['audits']['network-requests']['details']['items'] ?? []);
        $this->document_status_code = $networkRequests->first(fn ($it): bool => ($it['resourceType'] ?? null) === 'Document')['statusCode'] ?? 0;
        $this->has_metrika = $networkRequests->first(fn ($it): bool => str_contains(mb_strtolower($it['url'] ?? ''), 'metrika')) !== null;
        $this->has_vk_pixel = $networkRequests->first(fn ($it): bool => str_contains(mb_strtolower($it['url'] ?? ''), 'vk-rtrg')) !== null;

        if ($pageState !== null) {
            $this->simple_result = $action->handle($result, $pageState);
        }

        $failedInternalLinks = CrawledLink::getFailedLinksForUrl($this->link);
        if ($failedInternalLinks->count() === 0) {
            $this->internal_links = [
                'title' => 'Проверка битых ссылок (внутренних)',
                'score' => 1,
            ];
        } else {
            $this->internal_links = [
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
                    'items' => $failedInternalLinks
                        ->map(static fn (CrawledLink $link): array => ['url' => $link->url, 'status_code' => $link->status_code])
                        ->toArray(),
                ],
            ];
        }

        return $this->update();
    }

    public function history(): HasMany
    {
        return $this->hasMany(SeoAuditHistory::class);
    }

    public function isUpdateAvailable(): bool
    {
        return !($this->data_updated_at && $this->data_updated_at->diffInMinutes() < 10);
    }
}
