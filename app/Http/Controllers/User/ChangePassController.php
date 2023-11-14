<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;
use Module\User\Models\User;

class ChangePassController
{
    public function show(): Response
    {
        return Inertia::render('User/ChangePass');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_pass' => ['required', 'string', 'current_password'],
            'new_pass' => ['required', Password::defaults()],
        ]);

        /** @var User $user */
        $user = $request->user();

        $user->password = Hash::make($data['new_pass']);
        $user->save();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Пароль успешно изменён',
        ]);
    }
}
