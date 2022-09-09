<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    // Google Login
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    // Google Callback
    public function handleGoogleCallback()
    {
        // dd(request()->input('state'), request()->session()->get('state'));
        $user = Socialite::driver('google')->stateless()->user();

        $this->_registerOrLoginUser($user);
        // return home after login
        return redirect()->to('home');
    }


    // Facebook Login
    public function redirectToFaceook()
    {
        return Socialite::driver('facebook')->redirect();
    }
    // Facebook Callback
    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();
        $this->_registerOrLoginUser($user);
        // return home after login
        return redirect()->route('home');
    }


    // Github Login
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }
    // Github Callback
    public function handleGithubCallback()
    {
        $user = Socialite::driver('github')->user();
        $this->_registerOrLoginUser($user);
        // return home after login
        return redirect()->route('home');
    }

    protected function _registerOrLoginUser($data)
    {

        // dd($data);
        $user = User::where('email', '=', $data->email)->first();
        if (!$user) {
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->google_id = $data->id;
            $user->save();
        }
        Auth::login($user);
    }
}