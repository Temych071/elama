<?php

declare(strict_types=1);

namespace Reviews\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Campaign\Models\Campaign;
use Reviews\Models\AnswerTemplate;
use Reviews\Models\ReviewForm;

final class AnswerTemplatesController
{
    public function get(Campaign $campaign): iterable
    {
        return $campaign->reviewAnswerTemplates;
    }

    public function deleteTemplate(
        Campaign $campaign,
        AnswerTemplate $reviewAnswerTemplate
    ): RedirectResponse {
        $reviewAnswerTemplate->delete();

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Шаблон ответа успешно удалён.',
        ]);
    }

    public function createTemplate(Request $request, Campaign $campaign): RedirectResponse
    {
        $campaign->reviewAnswerTemplates()->create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string', 'max:2500'],
        ]));

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Шаблон ответа успешно создан.',
        ]);
    }

    public function updateTemplate(
        Request $request,
        Campaign $campaign,
        AnswerTemplate $reviewAnswerTemplate
    ): RedirectResponse {
        $reviewAnswerTemplate->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string', 'max:2500'],
        ]));

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Шаблон ответа успешно изменён.',
        ]);
    }
}
