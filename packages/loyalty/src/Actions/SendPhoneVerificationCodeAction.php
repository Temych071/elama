<?php

declare(strict_types=1);

namespace Loyalty\Actions;

use Illuminate\Support\Facades\RateLimiter;
use Loyalty\Contracts\SmsSenderService;
use Loyalty\Models\LoyaltyClient;

final class SendPhoneVerificationCodeAction
{
    public function execute(LoyaltyClient $loyaltyClient): bool
    {
        return RateLimiter::attempt('loyalty.public.send-code.'.$loyaltyClient->id, 1,
            static function () use ($loyaltyClient) {
                $code = random_int(1000, 9999);
                app(SmsSenderService::class)
                    ->send($loyaltyClient->phone, "Код для подтверждения номера телефона - $code.");
                $loyaltyClient->verify_code = $code;
                $loyaltyClient->verify_code_gen_at = now();
                $loyaltyClient->save();
            }, 60);
    }
}
