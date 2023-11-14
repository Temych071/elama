<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Public;

use App\Exceptions\ToastException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Loyalty\Actions\SendPhoneVerificationCodeAction;
use Loyalty\Models\Loyalty;
use Loyalty\Models\LoyaltyClient;

final class LoyaltyLoginController extends AbstractLoyaltyPublicController
{
    protected string $pageComponent = 'Loyalty/Public/Login';

    /**
     * @throws ToastException
     */
    public function login(Request $request, Loyalty $publicLoyalty): RedirectResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'starts_with:+7'],
        ]);

        /** @var LoyaltyClient $client */
        $client = LoyaltyClient::query()->firstOrCreate([
            'phone' => preg_replace('/[^\d+]+/', '', $request->input('phone')),
        ]);

        $sent = app(SendPhoneVerificationCodeAction::class)->execute($client);
        if (!$sent) {
            throw new ToastException(
                'Отправлять код можно не чаще раза в минуту. Повторите попытку позже.',
                route('loyalty.public.phone-verification.show', $publicLoyalty->id),
            );
        }
        Session::put('loyalty.auth.client-id', $client->id);

        return redirect()->route('loyalty.public.phone-verification.show', $publicLoyalty->id);
    }
}
