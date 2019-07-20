<?php

namespace App\Http\Controllers;

use App\Category;
use App\ProblemSolver;
use App\Report;
use App\Supervisor;
use App\Territory;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use stdClass;

class EmployeeController extends Controller
{
    /**
     * Získání seznamu zaměstnanců pro danou samosprávu. Zaměstnanci jsou předáváni ve skupinách podle jejich rolí.
     *
     * @param Territory $territory
     * @return Response
     */
    public function index(Territory $territory)
    {
        $category_id = Input::get('category_id');

        // k informacím mají přístup pouze zaměstnanci samosprávy
        if ($territory->admin_id === Auth::id() || $territory->approver_id === Auth::id()
            || $territory->supervisor()->where('user_id', Auth::id())->first()
            || $territory->problemSolver()->where('user_id', Auth::id())->first()) {

            // získání záznamu administrátora samosprávy
            $admin = DB::table('users')->select('id', 'avatarURL', 'name', 'email', 'telephone')->where('id', $territory->admin_id);

            // získání záznamu schvalovatele samosprávy
            $approver = DB::table('users')->select('id', 'avatarURL', 'name', 'email', 'telephone')->where('id', $territory->approver_id);

            $employees = new stdClass();

            // vybrání zaměstnanců, kteří jsou zodpovědni za řešení podnětů určité kategorie
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
                    ->groupBy('users.email')
                    ->get(['users.id', 'users.avatarURL', 'users.name', 'users.email', 'users.telephone']);
            }

            return response()->json([
                "employees" => $employees
            ], 200);

        } else {
            return abort(403);
        }
    }

    /**
     * Uložení nového zaměstnance samosprávy. Při přidávání se musí skontrolovat, zda tento nový zaměstnanec již nemá
     * nějakou roli v obci. Pokud ano, musí se odstranit a nahradit novou rolí.
     *
     * @param Request $request
     * @param Territory $territory
     * @return void
     */
    public function store(Request $request, Territory $territory)
    {

        // právo přidávat nové zaměstnance má pouze administrátor samosprávy
        if ($territory->admin_id === Auth::id()) {

            $newEmployee = $request->employee;

            $employeeRules = [
                'user_id' => 'required|integer',
                'role' => 'required|integer|min:1|max:3'
            ];

            $validatorModule = Validator::make($newEmployee, $employeeRules);
            if ($validatorModule->passes()) {
                if ($newEmployee['role'] == 1) { // nový zaměstnanec má být schvalovatel
                    $territory->update(['approver_id' => $newEmployee['user_id']]);

                    // smazání bývalých rolí
                    ProblemSolver::where('user_id', $newEmployee['user_id'])->delete();
                    Supervisor::where('user_id', $newEmployee['user_id'])->delete();
                } else if ($newEmployee['role'] == 2) {
                    foreach ($newEmployee['responsibilities'] as $category) {
                        ProblemSolver::create(['user_id' => $newEmployee['user_id'], 'category_id' => $category, 'territory_id' => $territory->id]);
                    }

                    // smazání bývalých rolí
                    Supervisor::where('user_id', $newEmployee['user_id'])->delete();
                    if($territory->approver_id == $newEmployee['user_id']) $territory->update(['approver_id' => null]);
                } else if ($newEmployee['role'] == 3) {
                    Supervisor::create(['user_id' => $newEmployee['user_id'], 'territory_id' => $territory->id]);

                    // smazání bývalých rolí
                    ProblemSolver::where('user_id', $newEmployee['user_id'])->delete();
                    if($territory->approver_id == $newEmployee['user_id']) $territory->update(['approver_id' => null]);
                }
            }

            $employee = User::where('id', $newEmployee['user_id'])->first(['id', 'avatarURL', 'name', 'email', 'telephone']);

            return response()->json([
                'employee' => $employee,
            ], 200);

        } else {
            return abort(403);
        }
    }

    /**
     * Získání konkrétního zaměstnance samosprávy.
     *
     * @param Territory $territory
     * @param User $employee
     * @return void
     */
    public function show(Territory $territory, User $employee)
    {
        // právo zobrazit zaměstnance mají jen zaměstnanci dané samosprávy
        if ($territory->admin_id === Auth::id() || $territory->approver_id === Auth::id()
            || $territory->supervisor()->where('user_id', Auth::id())->first()
            || $territory->problemSolver()->where('user_id', Auth::id())->first()) {

            unset($employee['email_verified_at'], $employee['created_at'], $employee['updated_at'], $employee['isSuperAdmin']);

            // přiřazení identifikátoru role zaměstnance
            if ($territory->admin_id == $employee->id) $employee->role = 0;
            else if ($territory->approver_id == $employee->id) $employee->role = 1;
            else if ($territory->problemSolver()->where('user_id', $employee->id)->first()) $employee->role = 2;
            else if ($territory->supervisor()->where('user_id', $employee->id)->first()) $employee->role = 3;

            // pokud je zaměstnanec řešitelem, předají se i kategorie, za které je zodpovědný
            if ($employee->role == 2) {
                $employee->responsibilities = DB::table('problem_solvers')
                    ->where('user_id', $employee->id)
                    ->pluck('category_id')
                    ->toArray();

                $employee->reports_assigned = $territory->reports()
                    ->select('id', 'created_at', 'title', 'state', 'userNote', 'employeeNote', 'address', 'user_id', 'category_id')
                    ->where('responsible_user_id', $employee->id)
                    ->get();
            }

            return response()->json([
                "employee" => $employee
            ], 200);

        } else {
            return abort(403);
        }

    }

    /**
     * Úprava záznamu zaměstnance. Role zaměstnance mohou být v průběhu jeho působení měněny.
     *
     * @param Request $request
     * @param Territory $territory
     * @param User $employee
     * @return void
     */
    public function update(Request $request, Territory $territory, User $employee)
    {
        // právo upravovat zaměstnance má pouze administrátor samosprávy
        if ($territory->admin_id === Auth::id()) {

            $newEmployee = $request->employee;

            $employeeRules = [
                'role' => 'required|integer|min:1|max:3'
            ];

            $validatorModule = Validator::make($newEmployee, $employeeRules);
            if ($validatorModule->passes()) {
                // určí se nová role zaměstnance a podle ní se vytvoří nové záznamy v databázi a odeberou se ty staré
                if ($newEmployee['role'] == 1) {
                    $territory->update(['approver_id' => $employee->id]);
                    Supervisor::where('user_id', $employee->id)->delete();
                    ProblemSolver::where('user_id', $employee->id)->delete();
                    Report::where('responsible_user_id', $employee->id)->update(['responsible_user_id' => null]);
                } else if ($newEmployee['role'] == 2) {
                    $categories = DB::table('categories')
                        ->join('problem_solvers', 'problem_solvers.category_id', '=', 'categories.id')
                        ->where('problem_solvers.user_id', $employee->id)
                        ->get(['categories.id']);

                    $categoryArray = array();
                    foreach ($categories as $category) {
                        array_push($categoryArray, $category->id);
                    }

                    // odstranit zodpovědnost řešitele za kategorie, které mu byly přiřazeny
                    $responsibilitiesToDel = array_diff($categoryArray, $newEmployee['responsibilities']);

                    foreach ($responsibilitiesToDel as $responsibility) {
                        ProblemSolver::where('user_id', $employee->id)->where('category_id', $responsibility)->delete();
                        Report::where('responsible_user_id', $employee->id)->where('category_id', $responsibility)->update(['responsible_user_id' => null]);
                    }

                    // nyní je třeba přidat zodpovědnost pro všechny nové kategorie, které mu byly přiřazeny
                    $responsibilitiesToAdd = array_diff($newEmployee['responsibilities'], $categoryArray);

                    foreach ($responsibilitiesToAdd as $responsibility) {
                        ProblemSolver::create(['user_id' => $employee->id, 'category_id' => $responsibility, 'territory_id' => $territory->id]);
                    }

                    // pokud byl zaměstnanec v minulosti supervizor, je nutné tuto roli odstranit
                    Supervisor::where('user_id', $employee->id)->delete();

                    if($territory->approver_id == $employee->id)
                        $territory->update(['approver_id' => null]);

                } else if ($newEmployee['role'] == 3 && !$territory->supervisor()->where('user_id', $employee->id)->first()) {
                    Supervisor::create(['user_id' => $employee->id, 'territory_id' => $territory->id]);

                    // pokud byl zaměstnanec v minulosti řešitelem je nutné odstranit všechny jeho zodpovědnosti - a to i za
                    // podněty, které měl k sobě přiřazené
                    ProblemSolver::where('user_id', $employee->id)->delete();
                    Report::where('responsible_user_id', $employee->id)->update(['responsible_user_id' => null]);
                    if($territory->approver_id == $employee->id)
                        $territory->update(['approver_id' => null]);
                }
            }

            // vrácení upraveného zaměstnance v odpovědi requestu

            unset($employee['email_verified_at'], $employee['created_at'], $employee['updated_at'], $employee['isSuperAdmin']);

            if ($territory->admin_id == $employee->id) $employee->role = 0;
            else if ($territory->approver_id == $employee->id) $employee->role = 1;
            else if ($territory->problemSolver()->where('user_id', $employee->id)->first()) $employee->role = 2;
            else if ($territory->supervisor()->where('user_id', $employee->id)->first()) $employee->role = 3;

            if ($employee->role == 2) {
                $employee->responsibilities = DB::table('problem_solvers')
                    ->where('user_id', $employee->id)
                    ->pluck('category_id')
                    ->toArray();

                $employee->reports_assigned = $territory->reports()
                    ->select('id', 'created_at', 'title', 'state', 'userNote', 'employeeNote', 'address', 'user_id', 'category_id')
                    ->where('responsible_user_id', $employee->id)
                    ->get();
            }

            return response()->json([
                "employee" => $employee
            ], 200);

        } else {
            return abort(403);
        }
    }


    /**
     * Odstranit záznam zaměstnance z databáze
     *
     * @param Territory $territory
     * @param User $employee
     * @return void
     */
    public function destroy(Territory $territory, User $employee)
    {
        // právo odstranit zaměstnance má pouze administrátor samosprávy
        if ($territory->admin_id === Auth::id()) {
            ProblemSolver::where('user_id', $employee->id)->delete();
            Supervisor::where('user_id', $employee->id)->delete();
            if ($territory->approver_id == $employee->id)
                $territory->update(['approver_id' => null]);

            return response()->json([
                "error" => false
            ], 200);
        } else {
            return abort(403);
        }
    }
}
