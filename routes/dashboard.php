<?php

use Module\User\Enums\UserRole;

require __DIR__ . '/modules/sources.php';

Route::prefix('admin')
    ->as('admin.')
    ->middleware(['hasRole:' . UserRole::admin->value])
    ->group(function () {
        require __DIR__ . '/modules/admin.php';
    });
