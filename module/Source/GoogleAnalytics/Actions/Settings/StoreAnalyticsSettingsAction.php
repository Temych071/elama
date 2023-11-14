<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Actions\Settings;

use Illuminate\Support\Facades\DB;
use Module\Source\GoogleAnalytics\Models\AnalyticsSettings;
use Module\Source\Sources\Actions\DispatchSourceFetchAction;

final class StoreAnalyticsSettingsAction
{
    public function __construct(
        private readonly DispatchSourceFetchAction $sourceFetchAction,
    ) {
    }

    public function execute(StoreAnalyticsSettingsCommand $command): AnalyticsSettings
    {
        $source = $command->campaign->googleAnalyticsSource;

        $settings = DB::transaction(static function () use ($command, $source) {
            if ($settings = $source->settings) {
                $settings->update($command->settingsAttributes());
            } else {
                $settings = AnalyticsSettings::create($command->settingsAttributes());
                $source->settings()->associate($settings)->save();
            }

            return $settings;
        });

        $this->sourceFetchAction->execute($source);

        return $settings;
    }
}
