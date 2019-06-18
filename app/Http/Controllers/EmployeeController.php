<?php

namespace App\Http\Controllers;

use App\Territory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use stdClass;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Territory $territory
     * @return Response
     */
    public function index(Territory $territory)
    {
        $category_id = Input::get('category_id');

        if ($territory->admin_id === Auth::id() || $territory->approver_id === Auth::id()
            || $territory->supervisor()->where('user_id', Auth::id())->first()
            || $territory->problemSolver()->where('user_id', Auth::id())->first()) {

            $admin = DB::table('users')->select('id', 'avatarURL', 'name', 'email', 'telephone')->where('id', $territory->admin_id);
            $approver = DB::table('users')->select('id', 'avatarURL', 'name', 'email', 'telephone')->where('id', $territory->approver_id);

            $employees = new stdClass();

            if ($category_id) {
                $employees = DB::table('users')
                    ->join('problem_solvers', function ($join) use ($category_id) {
                        $join->on('users.id', '=', 'problem_solvers.user_id')
                            ->where('problem_solvers.category_id', '=', $category_id);
                    })
                    ->join('territories', 'territories.id', '=', 'problem_solvers.territory_id')
                    ->union($admin)
                    ->union($approver)
                    ->get(['users.id', 'users.avatarURL', 'users.name', 'users.email', 'users.telephone']);
            } else {
                $employees->admin = $admin->get();
                $employees->approver = $approver->get();
                $employees->supervisors = DB::table('users')
                    ->where('users.id', "!=", $territory->admin_id)
                    ->where('users.id', '!=', $territory->approver_id)
                    ->join('supervisors', function ($join) {
                        $join->on('users.id', '=', 'supervisors.user_id');
                    })
                    ->join('territories', 'territories.id', '=', 'supervisors.territory_id')
                    ->get(['users.id', 'users.avatarURL', 'users.name', 'users.email', 'users.telephone']);

                $employees->problem_solvers = DB::table('users')
                    ->where('users.id', "!=", $territory->admin_id)
                    ->where('users.id', '!=', $territory->approver_id)
                    ->join('problem_solvers', function ($join) {
                        $join->on('users.id', '=', 'problem_solvers.user_id');
                    })
                    ->join('territories', 'territories.id', '=', 'problem_solvers.territory_id')
                    ->get(['users.id', 'users.avatarURL', 'users.name', 'users.email', 'users.telephone', 'problem_solvers.category_id']);
            }

            return response()->json([
                "employees" => $employees
            ], 200);

        } else {
            return abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Territory $territory
     * @param User $employee
     * @return void
     */
    public function show(Territory $territory, User $employee)
    {
        if ($territory->admin_id === Auth::id() || $territory->approver_id === Auth::id()
            || $territory->supervisor()->where('user_id', Auth::id())->first()
            || $territory->problemSolver()->where('user_id', Auth::id())->first()) {

            unset($employee['email_verified_at'], $employee['created_at'], $employee['updated_at'], $employee['isSuperAdmin']);

            $employee->isAdmin = $territory->admin_id === $employee->id;
            $employee->isApprover = $territory->approver_id === $employee->id;

            $employee->problem_solver = new stdClass();
            $employee->problem_solver->categories_assigned = DB::table('problem_solvers')
                ->where('user_id', $employee->id)
                ->pluck('category_id')
                ->toArray();

            $employee->isSupervisor = $territory->supervisor()->where('user_id', $employee->id)->first() ? true : false;

            $employee->reports_assigned = $territory->reports()
                ->select('id', 'created_at', 'title', 'state', 'userNote', 'employeeNote', 'address', 'user_id', 'category_id')
                ->where('responsible_user_id', $employee->id)
                ->get();

            return response()->json([
                "employee" => $employee
            ], 200);

        } else {
            return abort(403);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Territory $territory
     * @param User $user
     * @return void
     */
    public function update(Request $request, Territory $territory, User $user)
    {
        //
    }


    public function search(Request $request, Territory $territory)
    {
        if ($territory->admin_id === Auth::id()) {
            $users =  User::where('email','LIKE','%'.$request->email.'%')->get();

            return response()->json($users);
        } else {
            return abort(403);
        }
    }
}
