<?php

namespace App\Http\Controllers\Auth;

use App\Events\RegistrationFinishedEvent;
use App\Exceptions\BusinessException;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Module\User\Models\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class GoogleAuthController
{
    public function googleRedirect(): RedirectResponse
    {
        /** @var Provider $driver */
        $driver = Socialite::driver('google');
        return $driver->redirect();
    }

    public function googleCallback(): RedirectResponse
    {
        /** @var Provider $driver */
        $driver = Socialite::driver('google');

        $googleUser = $driver->user();

        $user = User::firstOrNew([
            'email' => $googleUser->getEmail(),
        ], [
            'password' => null,
            'phone' => null,
            'name' => $googleUser->getName(),
        ]);
        $user->email_verified_at = now();
        $user->save();

        Auth::login($user, true);

        return redirect(RouteServiceProvider::HOME);
    }

    public function registerFinishShow(): Response
    {
        return Inertia::render('Auth/RegisterFinish');
    }

    /**
     * @throws BusinessException|ValidationException
     */
    public function registerFinish(Request $request): RedirectResponse
    {
        $data = $request->all();
        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/\D/', '', (string) $data['phone']);
        }

        $data = validator($data, [
            'phone' => ['required', 'string', 'max:32', 'unique:users'],
            'password' => ['required', Password::defaults()],
        ])->validate();

        /** @var User $user */
        $user = $request->user();

        if ($user->password) {
            throw new BusinessException(trans('auth.errors.passAlreadySpecified'));
        }

        $data['password'] = Hash::make($data['password']);
        $user->update($data);

        event(new RegistrationFinishedEvent($user));

        return redirect()->route('dashboard');
    }
}
 