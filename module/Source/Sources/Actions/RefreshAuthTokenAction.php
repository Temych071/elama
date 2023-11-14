<?php

declare(strict_types=1);

namespace Module\Source\Sources\Actions;

use App\Exceptions\ToastException;
use Module\Campaign\Models\Campaign;
use Module\Source\GoogleAnalytics\Models\AnalyticsSettings;
use Module\Source\GoogleAnalytics\Services\GoogleAnalyticsService;
use Module\Source\Sources\Dto\AddAuthTokenDto;
use Module\Source\Sources\Models\Source;

final class RefreshAuthTokenAction
{
    public function execute(int $campaignId, string $sourceType, AddAuthTokenDto $newTokenDto): bool
    {
        /** @var Source $source */
        $source = Campaign::query()
            ->findOrFail($campaignId)
            ?->source($sourceType)
            ?->first();

        if (is_null($source)) {
            return false;
        }

        $oldToken = $source->authToken;

        if (!$oldToken->invalid) {
            throw new ToastException(
                userMessage: 'Текущий ключ доступа ещё действителен.',
                redirectTo: route('campaign.source', $campaignId)
            );
        }

        if (!$this->validateNewToken($source, $newTokenDto)) {
            throw new ToastException(
                userMessage: 'Для обновления доступа к источнику надо авторизоваться в том же аккаунте',
                redirectTo: route('campaign.source', $campaignId),
            );
        }

        $oldToken->refreshToken(
            token: $newTokenDto->getToken(),
            refreshToken: $newTokenDto->getRefreshToken(),
            expires_in: $newTokenDto->getExpiresIn(),
        );

        return true;
    }

    private function validateNewToken(Source $source, AddAuthTokenDto $new): bool
    {
        return match ($source->settings_type) {
            Source::TYPE_GOOGLE_ANALYTICS => $this->validateNewGoogleAnalyticsToken($source, $new),
            default => ((int)$source->authToken->user_id === (int)$new->getUserId()),
        };
    }

    private function validateNewGoogleAnalyticsToken(Source $source, AddAuthTokenDto $new): bool
    {
        /** @var AnalyticsSettings $settings */
        $settings = $source->settings;

        $accounts = app(GoogleAnalyticsService::class)
            ->connectUsingDto($new)
            ->analyticsService()
            ->management_accountSummaries
            ->listManagementAccountSummaries()
            ->getItems();

        foreach ($accounts as $account) {
            if ($account->getId() === $settings->google_id) {
                return true;
            }
        }

        return false;
    }
}
