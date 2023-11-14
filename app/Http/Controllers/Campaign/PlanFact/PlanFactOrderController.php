<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaign\PlanFact;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Campaign\Models\Campaign;

final class PlanFactOrderController
{
    public function store(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'array'],
            'order.*.num' => ['required', 'numeric'],
            'order.*.field' => ['nullable', 'string'],
        ]);

        $isEmpty = true;
        foreach ($data['order'] as $item) {
            if (!is_null($item['field'])) {
                $isEmpty = false;
                break;
            }
        }

        $settings = $campaign->planfact_settings;

        if (!$isEmpty) {
            $settings['order'] = $data['order'];
        } elseif (isset($settings['order'])) {
            unset($settings['order']);
        }

        $campaign->planfact_settings = $settings;
        $campaign->save();

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Порядок показателей успешно сохранён.',
        ]);
    }
}
