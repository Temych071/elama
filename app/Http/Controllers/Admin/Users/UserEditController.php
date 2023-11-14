<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Users;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Module\User\Enums\UserRole;
use Module\User\Enums\UserTariff;
use Module\User\Models\User;

final class UserEditController
{
    public function show(User $user): Response
    {
        return Inertia::render('Admin/Users/Form', [
            'userId' => $user->id,
            'userData' => $user,
            'roles' => UserRole::cases(),
            'tariffs' => UserTariff::cases(),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(User $user, Request $request): RedirectResponse
    {
        $data = $request->all();
        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/\D/', '', (string) $data['phone']);
        }

        $data = validator($data, [
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32', Rule::unique('users')->ignoreModel($user)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignoreModel($user)],
            'role' => ['required', 'string', new Enum(UserRole::class)],
            'tariff' => ['required', 'string', new Enum(UserTariff::class)],
        ])->validate();

        $user->update($data);

        return redirect()->route('admin.users.list')->with('toast', [
            'type' => 'success',
            'message' => trans('toasts.admin.users.updated'),
        ]);
    }

    public function delete(User $user): RedirectResponse
    {
        if ($user->delete()) {
            return redirect()->route('admin.users.list')->with('toast', [
                'type' => 'success',
                'message' => trans('toasts.admin.users.deleted'),
            ]);
        }
        return redirect()->route('admin.users.list')->with('toast', [
            'type' => 'error',
            'message' => 'При удалении произошла ошибка, повторите позже',
        ]);
    }
}
