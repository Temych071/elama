<?php

declare(strict_types=1);

namespace Reviews\Parsers\Gis\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;

final class DoubleGisPermissionsService extends DoubleGisBaseApiService
{
    public function getForPlace(string $placeId): array
    {
        return $this->prepareRequest()
            ->get("branches/$placeId/permissions")
            ->throw()
            ->json('result.items');
    }
}
