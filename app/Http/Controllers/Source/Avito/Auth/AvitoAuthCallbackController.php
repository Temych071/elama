<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\Avito\Auth;

use App\Http\Controllers\Source\Auth\AddSourceController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Source\Avito\Services\BaseAvitoService;
use Module\Source\Sources\Actions\AddSourceAction;
use Module\Source\Sources\Actions\RefreshAuthTokenAction;
use Module\Source\Sources\Models\Source;

final class AvitoAuthCallbackController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $projectId = $request->session()->get(AddSourceController::SESSION_CAMPAIGN_KEY);
        $request->session()->forget(AddSourceController::SESSION_CAMPAIGN_KEY);
        $code = $request->input('code');

        abort_if(empty($projectId) || empty($code), 404);

        $authToken = app(BaseAvitoService::class)->getUserToken($code);

        if (app(RefreshAuthTokenAction::class)->execute($projectId, Source::TYPE_AVITO, $authToken)) {
            return redirect()->route('campaign.source', $projectId)
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Обновление доступа к источнику прошло успешно',
                ]);
        }

        app(AddSourceAction::class)
            ->execute($projectId, Source::TYPE_AVITO, $authToken);

        return redirect()
            ->route('campaign.source', $projectId)
            ->with('toast', [
                'type' => 'success',
                'message' => 'Источник Авито успешно подключен к проекту.',
            ]);
    }
}
