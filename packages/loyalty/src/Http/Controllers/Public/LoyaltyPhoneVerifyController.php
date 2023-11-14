<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Public;

use App\Exceptions\ToastException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Loyalty\Actions\SendPhoneVerificationCodeAction;
use Loyalty\Models\Loyalty;
use Loyalty\Models\LoyaltyClient;

final class LoyaltyPhoneVerifyController extends AbstractLoyaltyPublicController
{
    protected string $pageComponent = 'Loyalty/Public/PhoneVerification';

    protected function getPageData(): array
    {
        $phone = $this->getClient($this->getLoyalty())->phone;
        return array_merge(parent::getPageData(), [
            'phone' => substr($phone, 0, 5) . '*****' . substr($phone, -2),
        ]);
    }

    protected function getClient(Loyalty $publicLoyalty): LoyaltyClient
    {
        /** @var LoyaltyClient $client */
        $client = LoyaltyClient::query()->findOr(
            Session::get('loyalty.auth.client-id'),
            static fn() => throw new ToastException(
                'Сначала введите номер',
                route('loyalty.public.login.show', $publicLoyalty->id),
            ),
        );
        return $client;
    }

    /**
     * @throws ToastException
     */
    public function send(Request $request, Loyalty $publicLoyalty): RedirectResponse
    {
        $code = $request->validate([
            'code' => ['required', 'string', 'size:4'],
        ])['code'];

        $client = $this->getClient($publicLoyalty);

        if ($client->verifyCode($code)) {
            Auth::guard('loyalty')->login($client, true);
        } else {
            throw new ToastException('Неверный код подтверждения.');
        }

        return redirect()->route('loyalty.public.card.show', $publicLoyalty->id);
    }

    public function resendCode(Loyalty $publicLoyalty): RedirectResponse
    {
        $client = $this->getClient($publicLoyalty);

        $sent = app(SendPhoneVerificationCodeAction::class)->execute($client);

        if (!$sent) {
            throw new ToastException('Отправлять код можно не чаще раза в минуту. Повторите попытку позже.');
        }

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Новый код для подтверждения отправлен.',
        ]);
    }
}
