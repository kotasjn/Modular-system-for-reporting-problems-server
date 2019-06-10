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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (!Auth::user()->isSuperAdmin) {
            return abort('403');
        }

        return response()->json([
            "territories" => Territory::all()
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isSuperAdmin) {
            return abort('403');
        }

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
     * Display the specified resource.
     *
     * @param Territory $territory
     * @return Response
     */
    public function show(Territory $territory)
    {
        if ($territory->admin_id === Auth::id() || $territory->approver_id === Auth::id() || $territory->supervisor()->where('user_id', Auth::id())->first()) {

            $territory->waiting_reports = Report::where('territory_id', $territory->id)->where('state', 0)->count();
            $territory->accepted_reports = Report::where('territory_id', $territory->id)->where('state', 1)->count();
            $territory->solved_reports = Report::where('territory_id', $territory->id)->where('state', 2)->count();
            $territory->rejected_reports = Report::where('territory_id', $territory->id)->where('state', 3)->count();

            unset($territory['location'], $territory['created_at'], $territory['updated_at']);

            return response()->json([
                "territory" => $territory
            ], 200);
        } else {
            return abort('403');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Territory $territory
     * @return Response
     */
    public function update(Request $request, Territory $territory)
    {
        if (!Auth::user()->isSuperAdmin) {
            return abort('403');
        }

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
     * Remove the specified resource from storage.
     *
     * @param Territory $territory
     * @return Response
     * @throws Exception
     */
    public function destroy(Territory $territory)
    {

        if (!Auth::user()->isSuperAdmin) {
            return abort('403');
        }

        $territory->delete();

        return response()->json([
            "error" => false
        ], 200);

    }


    public function getEmployees(Territory $territory)
    {
        $category_id = Input::get('category_id');

        if (!$category_id) {
            return response()->json([
                "error" => true,
                "message" => "Field category_id is necessarily for this request."
            ], 400);
        }

        if (($territory->admin_id === Auth::id() || $territory->approver_id === Auth::id())) {

            $admin = $territory->admin();
            $approover = $territory->approver();

            $employees = DB::table('users')
                ->join('problem_solvers', function($join) use ($category_id){
                    $join->on('users.id', '=', 'problem_solvers.user_id')
                    ->where('problem_solvers.category_id', '=', $category_id);
                })
                ->join('territories', 'territories.id', '=', 'problem_solvers.territory_id')
                ->union($admin)
                ->union($approover)
                ->get(['users.*']);


            return response()->json([
                "employees" => $employees
            ], 200);

        } else {
            return abort(403);
        }
    }
}
