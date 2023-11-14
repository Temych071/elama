<?php

declare(strict_types=1);

namespace Module\Source\Sources\Actions;

use App\Exceptions\BusinessException;
use Module\Campaign\Models\Campaign;
use Module\Source\Sources\Dto\AddAuthTokenDto;
use Module\Source\Sources\Models\Source;

final class AddSourceAction
{
    public function __construct(private readonly AddAuthTokenAction $addSourceAuthToken)
    {
    }

    public function execute(int $campaignId, string $type, AddAuthTokenDto $authToken): Source
    {
        $campaign = Campaign::findOrFail($campaignId);

        $this->abortIfExists($campaign, $type);

        $token = $this->addSourceAuthToken->execute($authToken);

        $source = Source::make();
        $source->settings_type = $type;
        $source->authToken()->associate($token);
        $campaign->sources()->save($source);

        return $source;
    }

    private function abortIfExists(Campaign $campaign, string $type): void
    {
        if ($campaign->sources()->where('settings_type', $type)->exists()) {
            throw new BusinessException(self::existsErrorMessage($type));
        }
    }

    private static function existsErrorMessage(string $type): string
    {
        return [
            Source::TYPE_YANDEX_METRIKA => 'К данной кампании уже привязана Яндекс.Метрика',
            Source::TYPE_YANDEX_DIRECT => 'К данной кампании уже привязан Яндекс.Директ',
            Source::TYPE_GOOGLE_ANALYTICS => 'К данной кампании уже привязан Google Analytics',
            Source::TYPE_GOOGLE_ADS => 'К данной кампании уже привязан Google Ads',
            Source::TYPE_VK => 'К данной кампании уже привязан ВКонтакте',
            Source::TYPE_AVITO => 'К данной кампании уже привязан Авито',
        ][$type];
    }
}
