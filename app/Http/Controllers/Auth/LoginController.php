<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Handle Social login request
     *
     * @param $social
     * @return response
     */

    public function socialLogin($social)
    {

        return Socialite::driver($social)->redirect();

    }

    /**
     * Obtain the user information from Social Logged in.
     * @param $social
     * @return Response
     */

    public function handleProviderCallback($social)
    {

        $userSocial = Socialite::driver($social)->user();

        $user = User::where(['email' => $userSocial->getEmail()])->first();

        if ($user) {

            Auth::login($user);

            return redirect()->action('DashboardController@index');

        } else {

            if($avatar = $userSocial->getAvatar()) {
                if ($social == 'google') {
                    $avatar = str_replace('?sz=50', '', $avatar);
                } elseif ($social == 'facebook') {
                    $avatar = str_replace('type=normal', 'type=large', $avatar);
                }
            }


            if ($social == 'google') {
                return view('auth.register')->withName($userSocial->getName())->withEmail($userSocial->getEmail())->withAvatar($avatar)->withGoogleID($userSocial->getId());
            } elseif ($social == 'facebook') {
                return view('auth.register')->withName($userSocial->getName())->withEmail($userSocial->getEmail())->withAvatar($avatar)->withFacebookID($userSocial->getId());
            }

        }

    }

}
