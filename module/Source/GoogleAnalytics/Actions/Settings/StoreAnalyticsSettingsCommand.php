<?php

declare(strict_types=1);

namespace Module\Source\GoogleAnalytics\Actions\Settings;

use Illuminate\Http\Request;
use Module\Campaign\Models\Campaign;
use Module\Source\GoogleAnalytics\Data\AnalyticsGoalData;
use Spatie\LaravelData\DataCollection;

final class StoreAnalyticsSettingsCommand
{
    public function __construct(
        public readonly Campaign $campaign,
        public readonly string $googleId,
        public readonly string $googleName,
        public readonly string $propertyId,
        public readonly string $propertyName,
        public readonly string $propertyInternalId,
        public readonly string $site,
        public readonly string $viewId,
        public readonly string $viewName,
        public readonly DataCollection $goals,
    ) {
    }

    public static function fromRequest(Request $request, Campaign $campaign): StoreAnalyticsSettingsCommand
    {
        return new self(
            campaign: $campaign,
            googleId: $request->input('account.id'),
            googleName: $request->input('account.name'),
            propertyId: $request->input('app.id'),
            propertyName: $request->input('app.name'),
            propertyInternalId: $request->input('app.internalWebPropertyId'),
            site: $request->input('app.websiteUrl'),
            viewId: $request->input('counter.id'),
            viewName: $request->input('counter.name'),
            goals: AnalyticsGoalData::collection($request->input('goals')),
        );
    }

    /**
     * @return array{google_id: string, google_name: string, property_id: string, property_name: string, property_internal_id: string, site: string, view_id: string, view_name: string, goals: \Spatie\LaravelData\DataCollection}
     */
    public function settingsAttributes(): array
    {
        return [
            'google_id' => $this->googleId,
            'google_name' => $this->propertyName,
            'property_id' => $this->propertyId,
            'property_name' => $this->propertyName,
            'property_internal_id' => $this->propertyInternalId,
            'site' => $this->site,
            'view_id' => $this->viewId,
            'view_name' => $this->viewName,
            'goals' => $this->goals,
        ];
    }
}
