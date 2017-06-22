<?php

namespace App\Http\Controllers\Auth;

use App\Company;
use App\Events\NewUserRegister;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|full_name|max:255',
            'company_name' => 'required|max:255',
            'job_title' => 'required|max:255',
            'company_website' => 'required|formatted_url',
            'company_type' => 'required',
            'phone' => 'required|phone_number',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        // turn the 'company_type' into the INT value
        if ($data['company_type'] === 'client') {
            $company_type = 1;
        } elseif ($data['company_type'] === 'provider') {
            $company_type = 2;
        } else {
            $company_type = 0;
        }

        // create a new company related to a new user
        $company = Company::create([
            'name' => $data['company_name'],
            'website' => $data['company_website'],
            'type' => $company_type
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'job_title' => $data['job_title'],
            'phone' => $data['phone'],
            'company_id' => $company->id
        ]);

        // Dispatch a new event for a registered user. (Notification Type: 8)
        event(new NewUserRegister($user));

        return $user;
    }

    /**
     *  Over-ridden the registered method from the "RegistersUsers" trait
     *  Remember to take care while upgrading laravel
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        User::all()->last()->toggleUser()->save();
        $this->guard()->login($user);

        if (Auth::user()->company()->type == 1) {
            return redirect()->route('client.dashboard');
        } else {
            return redirect()->route('provider.dashboard');
        }
    }
}
