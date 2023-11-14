<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Users;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Module\User\Models\User;

final class LoginAsUserController
{
    public function __invoke(User $user): RedirectResponse
    {
        // TODO: Возможно будет нужно какое-то ограничение по ролям

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME)->with('toast', [
            'type' => 'success',
            'message' => "Вы вошли как пользователь `{$user->name}`.",
        ]);
    }
}
