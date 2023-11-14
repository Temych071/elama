<?php

namespace App\Http\Controllers\Admin\Users;

use App\Events\RegistrationFinishedEvent;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Module\User\Enums\UserRole;
use Module\User\Enums\UserTariff;
use Module\User\Models\User;

class UserCreateController
{
    public function show(): Response
    {
        return Inertia::render('Admin/Users/Create', [
            'roles' => UserRole::cases(),
            'tariffs' => UserTariff::cases(),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->all();
        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/\D/', '', (string) $data['phone']);
        }

        $data = validator($data, [
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32', Rule::unique('users')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'role' => ['required', 'string', new Enum(UserRole::class)],
            'tariff' => ['required', 'string', new Enum(UserTariff::class)],
            'password' => ['required', Rules\Password::defaults()]
        ])->validate();

        $data['password'] = Hash::make((string)$data['password']);

        $user = User::query()->create($data);
        event(new RegistrationFinishedEvent($user));
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('admin.users.list')->with('toast', [
            'type' => 'success',
            'message' => trans('toasts.admin.users.created'),
        ]);
    }
}
