<?php

declare(strict_types=1);

namespace Module\Source\Vk\Actions\Settings;

use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;
use Module\Campaign\Models\Campaign;
use Module\Source\Sources\Actions\DispatchSourceFetchAction;
use Module\Source\Vk\Models\VkSettings;

final class StoreSettingsAction
{
    public function __construct(
        private readonly DispatchSourceFetchAction $sourceFetchAction,
    ) {
    }

    public function execute(Campaign $campaign, StoreVkSettingsCommand $command): VkSettings
    {
        $source = $campaign->vkSource;

        $settings = DB::transaction(static function () use ($command, $source) {
            if ($source->settings) {
                throw new BusinessException('Settings already exists');
            }

            $settings = VkSettings::create($command->settingsAttributes());
            $source->settings()->associate($settings)->save();

            return $settings;
        });

        $this->sourceFetchAction->execute($source);

        return $settings;
    }
}
