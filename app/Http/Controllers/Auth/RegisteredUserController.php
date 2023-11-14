<?php

namespace App\Http\Controllers\Auth;

use App\Events\RegistrationFinishedEvent;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Module\User\Models\User;

class RegisteredUserController
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->all();
        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/\D/', '', (string) $data['phone']);
        }

        $data = validator($data, [
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'max:32', 'unique:users'],
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
            'terms' => ['required', 'accepted']
        ])->validate();

        $user = User::create([
            'name' => $request->name ?? null,
            'phone' => $data['phone'],
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        event(new Registered($user));
        event(new RegistrationFinishedEvent($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
