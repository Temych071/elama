<?php

declare(strict_types=1);

namespace Reviews\Http\Middleware;

use Illuminate\Http\Request;
use Reviews\Models\ReviewForm;
use Module\Campaign\Models\Campaign;

final class HasReviewFormAccess
{
    public function handle(Request $request, callable $next): mixed
    {
        /** @var Campaign $project */
        $project = $request->route('campaign');

        /** @var ReviewForm $reviewForm */
        $reviewForm = $request->route('reviewForm');

        if ($project === null || $reviewForm === null) {
            return $next($request);
        }

        if ($reviewForm->project_id !== $project->id) {
            abort(404);
        }

        return $next($request);
    }
}
