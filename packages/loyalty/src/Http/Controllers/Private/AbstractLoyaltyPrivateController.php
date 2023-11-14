<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Private;

use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;
use Loyalty\Models\Loyalty;

abstract class AbstractLoyaltyPrivateController
{
    protected string $pageComponent = '';

    protected function getLoyalty(): Loyalty
    {
        /** @var Loyalty $loyalty */
        $loyalty = Request::route('loyalty');

        return $loyalty;
    }

    protected function getPageData(): array
    {
        return [
            'project' => Request::route('campaign'),
            'loyalties' => Request::route('campaign')
                ?->loyalties()
                ?->orderBy('created_at')
                ?->get(),

            'loyalty' => $this->getLoyalty(),
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
