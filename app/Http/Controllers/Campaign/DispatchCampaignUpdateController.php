<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaign;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Campaign\Models\Campaign;
use Module\Source\Sources\Actions\DispatchSourceFetchAction;
use Module\Source\Sources\Models\Source;

final class DispatchCampaignUpdateController
{
    public function __invoke(Request $request, Campaign $campaign): RedirectResponse
    {
        $campaign->sources->each(
            fn (Source $source) => app(DispatchSourceFetchAction::class)->execute($source)
        );

        return back();
    }
}
