<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Private;

use App\Exceptions\ToastException;
use Illuminate\Http\RedirectResponse;
use Loyalty\Actions\DispatchUpdateWalletClassAction;
use Loyalty\Http\Requests\LoyaltyFormSettingsRequest;
use Loyalty\Models\Loyalty;
use Module\Campaign\Models\Campaign;
use Throwable;

final class LoyaltyFormSettingsPrivateController extends AbstractLoyaltyPrivateController
{
    protected string $pageComponent = 'Loyalty/Private/FormSettings';

    /**
     * @throws Throwable
     */
    public function save(LoyaltyFormSettingsRequest $request, Campaign $project, Loyalty $loyalty): RedirectResponse
    {
        $loyalty->form_settings = $request->getFormSettings();

        $customFieldKeys = [
            'email' => true,
            'name' => true,
            'surname' => true,
            'birthday' => true,
            'gender' => true,
        ];
        foreach ($loyalty->form_settings->custom_fields as $customField) {
            if ($customFieldKeys[$customField->key] ?? false) {
                throw new ToastException('Ключи пользовательских полей не должны повторяться.');
            }
            $customFieldKeys[$customField->key] = true;
        }

        $loyalty->loadOrRemoveMedia(
            Loyalty::MEDIA_FORM_LOGO,
            $request->file('logo'),
            $request->boolean('logo_del')
        );
        $loyalty->saveOrFail();

        app(DispatchUpdateWalletClassAction::class)->execute($loyalty);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Настройки анкеты успешно сохранены',
        ]);
    }
}
