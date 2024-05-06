<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Uživatel existuje, přidáme pouze nové ID z poskytovatele
            if ($provider == 'google') {
                $user->google_id = $socialUser->getId();
            } elseif ($provider == 'facebook') {
                $user->facebook_id = $socialUser->getId();
            }
            $user->save();
            Auth::login($user, true);
            return redirect($this->redirectPath());
        } else {
            // Uživatel neexistuje, přesměrujeme na registrační formulář s předvyplněnými daty
            return redirect('/?showRegister=true')->with([
                'email' => $socialUser->getEmail(),
                'name' => $socialUser->getName(),
                $provider . '_id' => $socialUser->getId(), // Uloží buď 'google_id' nebo 'facebook_id'
                'provider' => $provider,
            ]);
        }
    }


}
