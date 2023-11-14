<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Private;

use Illuminate\Http\RedirectResponse;
use Loyalty\Actions\DispatchUpdateWalletClassAction;
use Loyalty\Http\Requests\LoyaltyCardSettingsRequest;
use Loyalty\Models\Loyalty;
use Module\Campaign\Models\Campaign;
use Throwable;

final class LoyaltyCardSettingsPrivateController extends AbstractLoyaltyPrivateController
{
    protected string $pageComponent = 'Loyalty/Private/CardSettings';

    /**
     * @throws Throwable
     */
    public function save(LoyaltyCardSettingsRequest $request, Campaign $project, Loyalty $loyalty): RedirectResponse
    {
        $loyalty->card_settings = $request->getCardSettings();
        $loyalty->loadOrRemoveMedia(
            Loyalty::MEDIA_CARD_LOGO,
            $request->file('logo'),
            $request->boolean('logo_del', false)
        );
        $loyalty->loadOrRemoveMedia(
            Loyalty::MEDIA_CARD_BANNER,
            $request->file('banner'),
            $request->boolean('banner_del', false)
        );
        $loyalty->saveOrFail();

        app(DispatchUpdateWalletClassAction::class)->execute($loyalty);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Настройки карты успешно сохранены',
        ]);
    }
}
