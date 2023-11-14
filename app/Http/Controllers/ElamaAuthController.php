<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Module\User\Models\User;
use Laravel\Socialite\Facades\Socialite;

use Exception;


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

class ElamaAuthController extends  Controller
{
	
	
	public function redirectPath()
    {
		print_r('redirectPath');
        if(Auth::user()->sign_up_complete == 1) {
            return '/c';
        } else {
           
        }
    }


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
        //$elamaUser = 'temych071@gmail.com'; 
        echo '</br>';echo '</br>';echo '</br>';
/**/
        $user = User::firstOrNew([
            'email' => $elamaUser,
        ], [
            'password' => null,
            'phone' => null,
            'name' => $elamaUser,
        ]);
        
        
        
        // // // // // // // // // // // // // // // // // // // // // //
       $user->email_verified_at = now();
$user->save();
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
        $value = $request->session()->get('url');
		$name = $request->input('user', $user);
		$request->session()->regenerate();
		$request->merge(['user' => $user ]);
		//print_r($request->all());
        //dd(Auth::user());
		//в админку обычного юзера не пустит. за это Module\User\Middleware\UserHasRole отвечает
		//return redirect(RouteServiceProvider::HOME);
        sleep(5);
        return redirect()->route('register-finish-elama');
    }


	public function registerFinishShow(): Response
    {
        
        print_r('ewrewrew');
        sleep(5);
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

        print_r($data);
        sleep(5);
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
 