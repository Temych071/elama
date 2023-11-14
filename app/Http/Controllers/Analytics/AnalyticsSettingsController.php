<?php

declare(strict_types=1);

namespace App\Http\Controllers\Analytics;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Module\Campaign\Models\Campaign;
use Module\Source\Analytics\Enums\CabinetItemType;

final class AnalyticsSettingsController
{
    public function load(Campaign $campaign): ?array
    {
        return $campaign->analytics_settings ?? null;
    }

    public function store(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['array'],
            'order.*.type' => ['string', new Enum(CabinetItemType::class)],
            'order.*.hidden' => ['boolean'],
        ]);

        $campaign->analytics_settings = $data;
        $campaign->save();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Настройки сохранены.',
        ]);
    }
}
