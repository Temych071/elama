<?php

declare(strict_types=1);

namespace Reviews\Parsers\Gis\Services;

use Illuminate\Http\Client\PendingRequest;

abstract class DoubleGisBaseApiService
{
    protected readonly string $apiUrl;
    private readonly DoubleGisAuthService $authService;

    public function __construct() {
        $this->authService = app(DoubleGisAuthService::class);
        $this->apiUrl = config('services.2gis.reviews_internal_api_url');
    }

    protected function prepareRequest(bool $authed = true): PendingRequest
    {
        return ($authed ? $this->authService->makeAuthedRequest() : new PendingRequest())
            ->baseUrl($this->apiUrl);
    }
}
