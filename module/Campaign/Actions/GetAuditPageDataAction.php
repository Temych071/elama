<?php

declare(strict_types=1);

namespace Module\Campaign\Actions;

use Illuminate\Support\Facades\Request;
use Module\LinkChecker\Models\SeoAdsAudit;
use Module\LinkChecker\Models\SeoAudit;
use Module\Campaign\Models\Campaign;

final class GetAuditPageDataAction
{
    public function execute(Campaign $project): array
    {
        return [
            'sourcesAuditSources' => SeoAdsAudit::query()
                ->with([
                    'source' => fn ($query) => $query->select([
                        'id',
                        'campaign_id',
                        'settings_type',
                    ]),
                    'seoAudits' => fn ($query) => $query->select([
                        'uuid',
                        'link',
                        'status',
                        'performance_score',
                        'seo_score',
                        'best_practices_score',
                        'document_status_code',
                        'has_metrika',
                        'has_vk_pixel',
                        'simple_result',
                        'data_updated_at',
                        'internal_links',
                    ]),
                    'jobBatch' => fn ($query) => $query->select([
                        'id',
                        'total_jobs',
                        'pending_jobs',
                    ]),
                ])
                ->whereRelation('source', 'campaign_id', $project->id)
                ->get(),
            'seoAuditHistory' => SeoAudit::query()
                ->whereRelation('history', 'user_id', '=', Request::user()->id)
                ->orderByDesc('created_at')
                ->limit(3)
                ->get([
                    'uuid',
                    'link',
                    'status',
                    'performance_score',
                    'seo_score',
                    'best_practices_score',
                    'document_status_code',
                    'has_metrika',
                    'has_vk_pixel',
                    'simple_result',
                    'data_updated_at',
                    'internal_links',
                ]),
        ];
    }
}
