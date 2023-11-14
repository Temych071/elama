<?php

declare(strict_types=1);

namespace SocialWidget\Http\Controllers\Private;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Module\Campaign\Models\Campaign;
use SocialWidget\Models\SocialWidget;

final class WidgetsController
{
    public function __invoke(Campaign $project): Response|RedirectResponse
    {
        $widgets = $project->socialWidgets()->orderBy('created_at')->get();

        if ($widgets->isNotEmpty()) {
            return redirect()->route('social-widget.private.stats', [
                $project,
                $widgets->first(),
            ]);
        }

        return Inertia::render('SocialWidget/Index', [
            'project' => $project,
            'widgets' => $widgets,
        ]);
    }

    public function root(Campaign $project): RedirectResponse
    {
        return redirect()->route('social-widget.private.index', $project);
    }

    public function createWidget(Request $request, Campaign $project): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $project->socialWidgets()->create($data);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Виджет успешно создан.',
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function deleteWidget(Campaign $project, SocialWidget $widget): RedirectResponse
    {
        $widget->deleteOrFail();

        return redirect()
            ->route('social-widget.private.index', $project)
            ->with('toast', [
                'type' => 'success',
                'message' => 'Виджет успешно удалён.',
            ]);
    }
}
