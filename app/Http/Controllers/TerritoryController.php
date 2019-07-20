<?php

namespace App\Http\Controllers;

use App\Report;
use App\Territory;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class TerritoryController extends Controller
{
    /**
     * Zobrazení seznamu samospráv
     *
     * @return Response
     */
    public function index()
    {
        // pouze administrátor systému může zobrazovat nové samosprávy
        if (!Auth::user()->isSuperAdmin) {
            return abort('403');
        }

        return response()->json([
            "territories" => Territory::all()
        ], 200);

    }

    /**
     * Uložení nového území
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // pouze administrátor systému může přidávat nové samosprávy
        if (!Auth::user()->isSuperAdmin) {
            return abort('403');
        }

        // vytvoření nové samosprávy
        $territory = Territory::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'avatarURL' => ['required', 'string', 'max:255'],
            'admin_id' => ['required', 'integer'],
            'approver_id' => ['required', 'integer']
        ]));

        return response()->json([
            "territory" => $territory
        ], 200);
    }

    /**
     * Zobrazení detailu samosprávy včetně počtů podnětů a zaměstnanců
     *
     * @param Territory $territory
     * @return Response
     */
    public function show(Territory $territory)
    {
        if ($territory->admin_id === Auth::id() || $territory->approver_id === Auth::id()
            || $territory->supervisor()->where('user_id', Auth::id())->first()
            || $territory->problemSolver()->where('user_id', Auth::id())->first()) {

            // zjištění počtu podnětů, které jsou přiděleny samosprávě
            $territory->waiting_reports = Report::where('territory_id', $territory->id)->where('state', 0)->count();
            $territory->accepted_reports = Report::where('territory_id', $territory->id)->where('state', 1)->count();
            $territory->solved_reports = Report::where('territory_id', $territory->id)->where('state', 2)->count();
            $territory->rejected_reports = Report::where('territory_id', $territory->id)->where('state', 3)->count();

            unset($territory['location'], $territory['created_at'], $territory['updated_at']);

            // získání administrátorů, správců, řešitelů a supervizorů samosprávy
            $admin = DB::table('users')
                ->select('users.id', 'users.avatarURL', 'users.name', 'users.email', 'users.telephone')
                ->where('id', $territory->admin_id);

            $approver = DB::table('users')
                ->select('users.id', 'users.avatarURL', 'users.name', 'users.email', 'users.telephone')
                ->where('id', $territory->approver_id);

            $supervisors = DB::table('users')
                ->select('users.id', 'users.avatarURL', 'users.name', 'users.email', 'users.telephone')
                ->join('supervisors', function($join) {
                    $join->on('users.id', '=', 'supervisors.user_id');
                })
                ->join('territories', 'territories.id', '=', 'supervisors.territory_id');

            $territory->employees =  DB::table('users')
                ->join('problem_solvers', function($join) {
                    $join->on('users.id', '=', 'problem_solvers.user_id');
                })
                ->join('territories', 'territories.id', '=', 'problem_solvers.territory_id')
                ->union($supervisors)
                ->union($admin)
                ->union($approver)
                ->get(['users.id', 'users.avatarURL', 'users.name', 'users.email', 'users.telephone']);


            return response()->json([
                "territory" => $territory
            ], 200);
        } else {
            return abort('403');
        }
    }

    /**
     * Aktualizace samosprávy
     *
     * @param Request $request
     * @param Territory $territory
     * @return Response
     */
    public function update(Request $request, Territory $territory)
    {
        // pouze administrátor systému může data samosprávy upravovat
        if (!Auth::user()->isSuperAdmin) {
            return abort('403');
        }

        // validace vstupů
        $territory->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'manufacturer' => ['required', 'string', 'max:255'],
            'material' => ['required', 'string', 'max:255'],
            'description' => ['string', 'max:255'],
            'category_id' => ['required', 'integer'],
        ]));

        return response()->json([
            "error" => false
        ], 200);
    }

    /**
     * Odstranění samosprávy z databáze
     *
     * @param Territory $territory
     * @return Response
     * @throws Exception
     */
    public function destroy(Territory $territory)
    {
        // odstranit samosprávu může pouze administrátor systému
        if (!Auth::user()->isSuperAdmin) {
            return abort('403');
        }

        // odstranění samosprávy
        $territory->delete();

        return response()->json([
            "error" => false
        ], 200);
    }
}
