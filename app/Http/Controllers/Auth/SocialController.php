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

class SocialController extends Controller
{
    public function redirectToProvider($provider)
    {
        /** @var Provider $driver */
        $driver = Socialite::driver('google');
        return $driver->redirect();
    }
}