<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Private;

use Illuminate\Http\Request;

final class LoyaltyIntegrationSettingsController extends AbstractLoyaltyPrivateController
{
    protected string $pageComponent = 'Loyalty/Private/IntegrationSettings';

    protected function getPageData(): array
    {
        return [
            ...parent::getPageData(),
            'loyalty' => $this->getLoyalty()
                ?->makeVisible('api_token'),

            'freeCardsCount' => $this->getLoyalty()->cards()
                ->whereHas('client', operator: '=', count: 0)
                ->count(),

            'lastCardSync' => $this->getLoyalty()
                    ->cards()
                    ->latest('updated_at')
                    ->first()?->updated_at ?? null,
        ];
    }
}
