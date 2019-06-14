<?php

namespace App\Http\Controllers;

use App\Input;
use App\Item;
use App\Module;
use App\Territory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Territory $territory
     * @return Response
     */
    public function index(Territory $territory)
    {

        if ($territory->admin_id === Auth::id()) {

            $modules = $territory->modules()->get();

            foreach ($modules as $module) {
                unset($module->territory_id, $module->created_at, $module->updated_at);

                $module->inputs = Input::where('module_id', $module->id)->get();

                foreach ($module->inputs as $input) {
                    unset($input->module_id, $input->created_at, $input->updated_at);
                    $input->items = Item::where('input_id', $input->id)->get();

                    foreach ($input->items as $item)
                        unset($item->input_id, $item->created_at, $item->updated_at);
                }
            }

            return response()->json([
                'modules' => $modules,
            ], 200);
        }

        return abort('403');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Territory $territory
     * @return void
     */
    public function store(Request $request, Territory $territory)
    {
        //TODO
    }

    /**
     * Display the specified resource.
     *
     * @param Territory $territory
     * @param Module $module
     * @return Response
     */
    public function show(Territory $territory, Module $module)
    {
        if ($territory->admin_id === Auth::id()) {

            unset($module->territory_id, $module->created_at, $module->updated_at);

            $module->inputs = Input::where('module_id', $module->id)->get();

            foreach ($module->inputs as $input) {
                unset($input->module_id, $input->created_at, $input->updated_at);
                $input->items = Item::where('input_id', $input->id)->get();

                foreach ($input->items as $item)
                    unset($item->input_id, $item->created_at, $item->updated_at);
            }

            return response()->json([
                'module' => $module,
            ], 200);
        }

        return abort('403');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Territory $territory
     * @param Module $module
     * @return void
     */
    public function update(Request $request, Territory $territory, Module $module)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Territory $territory
     * @param Module $module
     * @return void
     */
    public function destroy(Territory $territory, Module $module)
    {
        //
    }
}
