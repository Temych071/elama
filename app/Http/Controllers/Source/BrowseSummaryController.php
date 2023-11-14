<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source;

use Illuminate\Http\Request;
use Module\Campaign\Models\Campaign;
use Module\Source\Sources\Models\Source;

final class BrowseSummaryController
{
    public function __invoke(Request $request, Campaign $campaign)
    {
        $sources = $campaign->sources;

        $request->session()->reflash();

        if ($sources->contains('settings_type', Source::TYPE_GOOGLE_ANALYTICS)) {
            return redirect()->route('campaign.browse.analytics', $campaign);
        }

//        if ($sources->contains('settings_type', Source::TYPE_YANDEX_METRIKA)) {
        return redirect()->route('campaign.browse.metrika', $campaign);
//        }

//        abort(404);
    }
}
