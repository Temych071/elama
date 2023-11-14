<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Public;

use Inertia\Inertia;
use Inertia\Response;
use Loyalty\Models\Loyalty;
use Request;

abstract class AbstractLoyaltyPublicController
{
    protected string $pageComponent = '';

    protected function getLoyalty(): Loyalty
    {
        /** @var Loyalty $loyalty */
        $loyalty = Request::route('publicLoyalty');

        return $loyalty;
    }

    protected function getPageData(): array
    {
        return [
            'loyalty' => $this->getLoyalty()
                ->only([
                    'id',

                    'form_settings',
                    'form_logo_url',

                    'card_settings',
                    'card_logo_url',
                    'card_banner_url',
                ]),
        ];
    }

    public function show(): Response
    {
        return Inertia::render($this->getPageComponent(), $this->getPageData());
    }

    protected function getPageComponent(): string
    {
        return $this->pageComponent;
    }
}
