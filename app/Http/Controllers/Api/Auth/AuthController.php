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
     * Registrace uživatele
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
     * Přihlášení uživatele a vytvoření přístupového tokenu
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

        // pokud přihlašovací údaje nesedí, odešle se informace uživateli
        if (!Auth::attempt($credentials))
            return response()->json([
                'error' => true,
                'message' => 'Uživatel není autorizován!'
            ], 401);

        $user = $request->user();

        unset($user['email_verified_at'], $user['created_at'], $user['updated_at']);

        // vytvoření přístupového tokenu pro přihlášeného uživatele
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);

        $token->save();

        $user->isSuperAdmin = ($user->isSuperAdmin) ? true : false;


        // do odpovědi se přidá i seznam samospráv, kde uživatel nabývá nějaké funkce
        $territoriesAdmin = DB::table('territories')
            ->select('id', 'avatarURL', 'name', 'approver_id', 'admin_id')
            ->where('admin_id', Auth::id());

        $territoriesApprover = DB::table('territories')
            ->select('id', 'avatarURL', 'name', 'approver_id', 'admin_id')
            ->where('approver_id', Auth::id());

        $territoriesSolvers = DB::table('territories')
            ->join('problem_solvers', function ($join) {
                $join->on('territories.id', '=', 'problem_solvers.territory_id')
                    ->where('problem_solvers.user_id', Auth::id());
            })
            ->select('territories.id', 'territories.avatarURL', 'territories.name', 'territories.approver_id', 'territories.admin_id');

        // spojení vybraných záznamů
        $territories = DB::table('territories')
            ->join('supervisors', function ($join) {
                $join->on('territories.id', '=', 'supervisors.territory_id')
                    ->where('supervisors.user_id', Auth::id());
            })
            ->union($territoriesAdmin)
            ->union($territoriesApprover)
            ->union($territoriesSolvers)
            ->get(['territories.id', 'territories.avatarURL', 'territories.name', 'territories.approver_id', 'territories.admin_id']);


        // pokud seznam samospráv není prázdný, vytvoří se seznam dalších zaměstnanců, kteří pro obec také pracují
        if (count($territories)) {
            $user->territories = $territories;
            foreach ($user->territories as $territory) {
                $territory->waiting_reports = Report::where('territory_id', $territory->id)->where('state', 0)->count();
                $territory->accepted_reports = Report::where('territory_id', $territory->id)->where('state', 1)->count();
                $territory->solved_reports = Report::where('territory_id', $territory->id)->where('state', 2)->count();
                $territory->rejected_reports = Report::where('territory_id', $territory->id)->where('state', 3)->count();

                $admin = DB::table('users')
                    ->select('users.id', 'users.avatarURL', 'users.name', 'users.email', 'users.telephone')
                    ->where('id', $territory->admin_id);

                $approver = DB::table('users')
                    ->select('users.id', 'users.avatarURL', 'users.name', 'users.email', 'users.telephone')
                    ->where('id', $territory->approver_id);

                $supervisors = DB::table('users')
                    ->select('users.id', 'users.avatarURL', 'users.name', 'users.email', 'users.telephone')
                    ->join('supervisors', function ($join) {
                        $join->on('users.id', '=', 'supervisors.user_id');
                    })
                    ->join('territories', 'territories.id', '=', 'supervisors.territory_id');

                $territory->employees = DB::table('users')
                    ->join('problem_solvers', function ($join) {
                        $join->on('users.id', '=', 'problem_solvers.user_id');
                    })
                    ->join('territories', 'territories.id', '=', 'problem_solvers.territory_id')
                    ->union($supervisors)
                    ->union($admin)
                    ->union($approver)
                    ->groupBy('users.email')
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
     * Odhlášení uživatele
     *
     * @param Request $request
     * @return JsonResponse [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'error' => false,
            'message' => 'Uživatel byl úspěšně odhlášen!'
        ], 200);
    }

    /**
     * Získání přihlášeného uživatele
     *
     * @param Request $request
     * @return JsonResponse [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}