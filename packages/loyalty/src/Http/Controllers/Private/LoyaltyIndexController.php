<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Private;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Loyalty\Actions\CreateLoyaltyAction;
use Loyalty\Actions\DispatchUpdateWalletClassAction;
use Loyalty\Models\Loyalty;
use Module\Campaign\Models\Campaign;
use Throwable;

final class LoyaltyIndexController
{
    public function __invoke(Campaign $project): Response|RedirectResponse
    {
        $loyalty = $project->loyalties()->first();
        if ($loyalty) {
            return redirect()->route('loyalty.private.loyalty.show', [$project->id, $loyalty->id]);
        }

        return Inertia::render('Loyalty/Private/Index', [
            'project' => $project,
            'loyalties' => $project->loyalties()->orderBy('created_at')->get(),
        ]);
    }

    public function showLoyalty(Campaign $project, Loyalty $loyalty): RedirectResponse
    {
        return redirect()->route('loyalty.private.loyalty.form-settings.show', [$loyalty->project_id, $loyalty->id]);
    }

    public function create(Request $request, Campaign $project): RedirectResponse
    {
        $name = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ])['name'];

        $loyalty = app(CreateLoyaltyAction::class)->execute($project, $name);

        app(DispatchUpdateWalletClassAction::class)->execute($loyalty, true);

        return redirect()->route('loyalty.private.loyalty.show', [$loyalty->project_id, $loyalty->id])->with('toast', [
            'type' => 'success',
            'message' => 'Программа лояльности успешно создана',
        ]);
    }

    public function remove(Campaign $project, Loyalty $loyalty): RedirectResponse
    {
        $loyalty->deleteOrFail();

        return redirect()->route('loyalty.private.loyalty.index', [$project->id])->with('toast', [
            'type' => 'success',
            'message' => 'Программа лояльности успешно удалена',
        ]);
    }
}
