<?php
/**
 * Created by PhpStorm.
 * User: Honza
 * Date: 12.02.2019
 * Time: 21:08
 */

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|',
            'email' => 'required|string|email|unique:users',
            'telephone' => 'required|numeric|digits:9',
            'password' => 'required|string|confirmed'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'avatarURL' => isset($request->avatarURL) ? $request->avatarURL : 'https://res.cloudinary.com/kotik/image/upload/v1548981829/Images/default-profile.jpg',
            'password' => bcrypt($request->password)
        ]);

        $user->save();

        return response()->json([
            'error' => false,
            'message' => 'Registrace proběhla úspěšně!'
        ], 201);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials))
            return response()->json([
                'error' => true,
                'message' => 'Uživatel není autorizován!'
            ], 401);

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);

        $token->save();

        $user->isSuperAdmin = ($user->isSuperAdmin) ? true : false;
        $user->isEmployee = ($user->isEmployee) ? true : false;
        $user->isSupervisor = ($user->isSupervisor) ? true : false;

        return response()->json([
            'error' => false,
            'message' => 'Uživatel přihlášen!',
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => $user,
        ]);
    }

    public function socialLogin($social)
    {

        return Socialite::driver($social)->redirect();

    }




    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'error' => false,
            'message' => 'Uživatel byl úspěšně odhlážen!'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}