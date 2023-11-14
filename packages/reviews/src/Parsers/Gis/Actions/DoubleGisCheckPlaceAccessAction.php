<?php

declare(strict_types=1);

namespace Reviews\Parsers\Gis\Actions;

use Illuminate\Support\Arr;
use Reviews\Parsers\Gis\Services\DoubleGisPermissionsService;

final class DoubleGisCheckPlaceAccessAction
{
    public const NEEDED_PERMISSIONS = [
        'branch.view',
        'branch.view-review',
        'branch.edit',

        // Не уверен, но вроде как ответы относятся к атрибутам
        'branch.view-attribute',
        'branch.edit-attribute',
    ];

    public function execute(string $placeId): bool
    {
        $perms = app(DoubleGisPermissionsService::class)
            ->getForPlace($placeId);
        $perms = Arr::only($perms, self::NEEDED_PERMISSIONS);

        foreach ($perms as $perm) {
            if (!$perm) {
                return false;
            }
        }

        return true;
    }
}
