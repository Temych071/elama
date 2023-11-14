<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Module\User\Models\SettingsRequest;
use Throwable;

final class SettingsRequestsController
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/SettingsRequests', [
            'requests' => SettingsRequest::query()
                ->latest()
                ->with(['user', 'campaign'])
                ->get(),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function delete(Request $request): RedirectResponse
    {
        $request_id = $request->validate([
            'request_id' => ['required', 'numeric', Rule::exists('settings_requests', 'id')],
        ])['request_id'];

        $settingsRequest = SettingsRequest::query()->findOrFail($request_id);

        $settingsRequest->deleteOrFail();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => trans('toasts.settingsRequests.admin.deleted')
        ]);
    }
}
