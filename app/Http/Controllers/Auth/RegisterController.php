<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Notifications\userActivateNotification;
use App\Models\ActivationToken;
use App\Models\Role;






class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/';

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'size:5'],
            'phone' => ['required', 'string', 'size:9', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['string', 'min:8', 'confirmed'],
        ]);
    }


    protected function create(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'address' => $data['address'],
            'city' => $data['city'],
            'zip' => $data['zip'],
            'phone' => $data['phone'],
            'status' => 'unactivated',
            'email' => $data['email'],
            'password' => isset($data['password']) ? bcrypt($data['password']) : null,
            'google_id' => $data['google_id'],
            'facebook_id' => $data['facebook_id'],
        ]);


        $user->roles()->attach(1);



        $activationToken = ActivationToken::create([
            'user_id' => $user->id,
            'token' => Str::random(60),
            'used' => false
        ]);


        $user->notify(new userActivateNotification($activationToken->token));

        return $user;
    }

}
