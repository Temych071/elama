<?php

declare(strict_types=1);

namespace App\Http\Controllers\SeoAudits;

use Illuminate\Http\Request;
use Module\LinkChecker\Models\SeoAudit;

final class SeoAuditIndexController
{
    public function __invoke(Request $request)
    {
        return inertia('SeoAudits/SeoAudit', [
            'history' => SeoAudit::query()
                ->whereRelation('history', 'user_id', '=', $request->user()->id)
                ->orderByDesc('created_at')
                ->limit(10)
                ->get(['uuid', 'status', 'link', 'data_updated_at']),
        ]);
    }
}
