<?php
/**
 * Created by PhpStorm.
 * User: Honza
 * Date: 12.02.2019
 * Time: 21:08
 */

namespace App\Http\Controllers\Api\Auth;

use App\Report;
use App\Supervisor;
use App\Territory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param Request $request
     * @return JsonResponse [string] message
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
     * @param Request $request
     * @return JsonResponse [string] access_token
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

        $territoriesAdmin = Territory::select('id', 'name', 'avatarURL', 'approver_id', 'admin_id')->where('admin_id', Auth::id());
        $user->territories = Territory::select('id', 'name', 'avatarURL', 'approver_id', 'admin_id')->where('approver_id', Auth::id())->union($territoriesAdmin)->get();

        if (count($user->territories)) {
            foreach ($user->territories as $territory) {
                $territory->waiting_reports = Report::where('territory_id', $territory->id)->where('state', 0)->count();
                $territory->accepted_reports = Report::where('territory_id', $territory->id)->where('state', 1)->count();
                $territory->solved_reports = Report::where('territory_id', $territory->id)->where('state', 2)->count();
                $territory->rejected_reports = Report::where('territory_id', $territory->id)->where('state', 3)->count();

                $admin = DB::table('users')->select('id', 'avatarURL', 'name', 'email', 'telephone')->where('id', $territory->admin_id);
                $approover = DB::table('users')->select('id', 'avatarURL', 'name', 'email', 'telephone')->where('id', $territory->aproover_id);

                $territory->employees  = DB::table('users')
                    ->join('problem_solvers', function($join) {
                        $join->on('users.id', '=', 'problem_solvers.user_id');
                    })
                    ->join('territories', 'territories.id', '=', 'problem_solvers.territory_id')
                    ->union($admin)
                    ->union($approover)
                    ->get(['users.id', 'users.avatarURL', 'users.name', 'users.email', 'users.telephone']);
            }
        }

        return response()->json([
            'error' => false,
            'message' => 'Uživatel přihlášen!',
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => $user
        ], 200);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @param Request $request
     * @return JsonResponse [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'error' => false,
            'message' => 'Uživatel byl úspěšně odhlážen!'
        ], 200);
    }

    /**
     * Get the authenticated User
     *
     * @param Request $request
     * @return JsonResponse [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}