<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source\Auth;

use App\Exceptions\BusinessException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
use Module\Billing\Subscription\Enums\SubscriptionStatus;
use Module\Source\Adsense\Services\AdsenseAuthService;
use Module\Source\Avito\Services\BaseAvitoService;
use Module\Source\GoogleAnalytics\Services\GoogleAnalyticsService;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Services\VkService;
use Module\Campaign\Models\Campaign;
use Symfony\Component\HttpFoundation\Response;
use Module\User\Models\User;

final class AddSourceController
{
    public const SESSION_CAMPAIGN_KEY = 'add-source.campaign';
    public const SESSION_TYPE_KEY = 'add-source.type';

    public const SOURCES_BLOCKED_BY = [
        'yandex-metrika' => ['google-analytics'],
        'google-analytics' => ['yandex-metrika'],
    ];

    /**
     * @throws BusinessException
     */
    public function __invoke(Request $request): Response
    {
        ['type' => $type, 'campaign_id' => $campaignId] = $request->validate([
            'campaign_id' => 'required|int',
            'type' => ['required', 'string'],
        ]);

        /** @var User $user */
        $user = $request->user();
        /** @var Campaign $project */
        $project = $user->campaigns()->findOrFail($campaignId);

        $hasActiveSub = $project
            ->activeSubscription()
            ->where('status', SubscriptionStatus::active)
            ->exists();
        if (!$hasActiveSub) {
            return redirect()->route('user.billing.new-payment.show')->with('toast', [
                'type' => 'warning',
                'message' => 'Для подключения источников у проекта должен быть активный тариф',
            ]);
        }

        if (isset(self::SOURCES_BLOCKED_BY[$type])) {
            $isLocked = Source::query()
                ->where('campaign_id', $campaignId)
                ->whereIn('settings_type', self::SOURCES_BLOCKED_BY[$type])
                ->exists();

            if ($isLocked) {
                throw new BusinessException('Can\'t connect this source.');
            }
        }

        $request->session()->put(self::SESSION_CAMPAIGN_KEY, $campaignId);
        $request->session()->put(self::SESSION_TYPE_KEY, $type);

        $driver = config("sources.list.$type.driver");

        if ($driver === 'source_yandex') {
            return Inertia::location($this->yandexMetrikaUrl($driver));
        }

        if ($driver === 'source_google') {
            if ($type === Source::TYPE_GOOGLE_ANALYTICS) {
                return Inertia::location($this->googleAnalyticsUrl());
            }

            if ($type === Source::TYPE_GOOGLE_ADS) {
                return Inertia::location($this->googleAdsUrl());
            }
        }

        if ($driver === 'source_vk') {
            return Inertia::location($this->vkUrl());
        }

        if ($driver === 'source_avito') {
            return Inertia::location($this->avitoUrl());
        }

        throw new BusinessException('Unexpected auth driver');
    }

    private function yandexMetrikaUrl(string $driverName): string
    {
        /** @var AbstractProvider $driver */
        $driver = Socialite::driver($driverName);

        return $driver
            ->with(['force_confirm' => 1])
            ->redirect()
            ->getTargetUrl();
    }

    private function googleAnalyticsUrl(): string
    {
        return app(GoogleAnalyticsService::class)->client()->createAuthUrl();
    }

    private function googleAdsUrl(): string
    {
        return app(AdsenseAuthService::class)->getAuthUrl();
    }

    private function avitoUrl(): string
    {
        return app(BaseAvitoService::class)->getAuthUrl();
    }

    private function vkUrl(): string
    {
        return app(VkService::class)->getAuthUrl();
    }
}
