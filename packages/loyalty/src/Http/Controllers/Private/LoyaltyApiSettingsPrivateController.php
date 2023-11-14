<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Private;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Loyalty\Models\Loyalty;

final class LoyaltyApiSettingsPrivateController extends AbstractLoyaltyPrivateController
{
    // TODO: Нужен дизайн страницы настройки API:
    //         - Отображение API-токена
    //         - Обновление(сброс) API-токена
    //         - Импорт карт
    //         - Дата последней синхронизации
    //         - ...
    protected string $pageComponent = 'Loyalty/Private/ApiSettings';

    public function resetToken(Loyalty $loyalty): RedirectResponse
    {
        $loyalty->api_token = Loyalty::genApiToken();
        $loyalty->save();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'API-токен успешно обновлён.',
        ]);
    }

    public function importCards(Request $request): RedirectResponse
    {
        // TODO: ...
        //       В каком формате?

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Карты лояльности успешно импортированы.',
        ]);
    }
}
