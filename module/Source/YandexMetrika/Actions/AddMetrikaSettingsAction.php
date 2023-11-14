<?php

declare(strict_types=1);

namespace Module\Source\YandexMetrika\Actions;

use Illuminate\Support\Facades\DB;
use Module\Campaign\Models\Campaign;
use Module\Source\Sources\Actions\DispatchSourceFetchAction;
use Module\Source\YandexMetrika\Data\CounterData;
use Module\Source\YandexMetrika\Models\MetrikaSourceSettings;
use Spatie\LaravelData\DataCollection;

final class AddMetrikaSettingsAction
{
    public function __construct(
        private readonly DispatchSourceFetchAction $sourceFetchAction,
    ) {
    }

    public function execute(Campaign $campaign, CounterData $counterDto, ?DataCollection $goals): MetrikaSourceSettings
    {
        $source = $campaign->metrikaSource()->firstOrFail();

        $data = [
            ...$counterDto->exclude('goals')->all(),
            'counter_id' => $counterDto->id,
            'goals' => $goals,
        ];

        $isNew = false;
        $settings = DB::transaction(static function () use ($source, $data, &$isNew) {
            if ($settings = $source->settings) {
                $settings->update($data);
            } else {
                $settings = MetrikaSourceSettings::create($data);
                $source->settings()->associate($settings)->save();
                $isNew = true;
            }

            return $settings;
        });

        if ($isNew) {
            $this->sourceFetchAction->execute($source);
        }

        return $settings;
    }
}
