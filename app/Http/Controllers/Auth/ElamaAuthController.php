<?php

namespace App\Http\Controllers\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Module\User\Models\User;
use Laravel\Socialite\Facades\Socialite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
 
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\Provider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/*
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

*/
/*
        $userEl = ElamaUser::where('email', '=',  $elamaUser)->first();
        if ($userEl === null) {
            DB::table('elama_users')->insert(
                ['id' => 1, 'name' => $elamaUser]
            );
        }*/

        /*
         // Вариант 3
        if (Auth::attempt(['email' => $elamaUser, 'password' => null])) {
            // Аутентификация успешна...
            return redirect(RouteServiceProvider::HOME)->with('toast', [
                'type' => 'success',
                'message' => "Вы вошли как пользователь `{$user->name}`.",
            ]);
        }
        // // // // // // // // // // // // // // // // // // // // // //
*/


use Symfony\Component\HttpFoundation\RedirectResponse;

class ElamaAuthController
{
    public function elamaRedirect()
    {
        //** @var Provider $driver */
        $driver = Socialite::driver('elama');
        return $driver->redirect();
    }

    public function elamaCallback(Request $request)
    {
        echo '</br>';echo '</br>';echo '</br>';
        // // // // // // // // // // // // // // // // // // // // // //
         // Создаю пользователя
        $elamaUser = Socialite::driver('elama')->user(); 
        $elamaUser = 'temych071@gmail.com'; 
        echo '</br>';echo '</br>';echo '</br>';
/**/
        $user = User::firstOrNew([
            'email' => $elamaUser,
        ], [
            'password' => null,
            'phone' => null,
            'name' => $elamaUser,
        ]);
        
        
        $user->save();
        // // // // // // // // // // // // // // // // // // // // // //
       $user->email_verified_at = now();

       //Auth::login(User::where('email', $elamaUser)->first());
        Auth::login($user, true);
        // // // // // // // // // // // // // // // // // // // // // //
         // Создаем пользователя
        // Вариант 1
        //
        
        /*
        ПРОВЕРИТЬ!!!!!
        protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... Другие провайдеры
        \SocialiteProviders\VKontakte\VKontakteExtendSocialite::class.'@handle',
    ],
];
*/
        // Вариант 2
        //
        //
        $data = $request->session()->all();

        print_r($request->user());
        $request->session()->regenerate();
           echo "</br>";
           print_r($data);
        
       //print_r($data);
        //dd(Auth::user());
        return redirect()->to('/c');
    }

}
 