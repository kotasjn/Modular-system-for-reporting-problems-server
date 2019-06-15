<?php

namespace App\Http\Controllers;

use App\Input;
use App\Item;
use App\Module;
use App\Territory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Validator;

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
        if ($territory->admin_id === Auth::id()) {

            $moduleNew = $request->module;

            $moduleRules = [
                'name' => 'required|string|max:80',
                'category_id' => 'required|integer'
            ];

            $inputRules = [
                'title' => 'required|string|max:80',
                'inputType' => 'required|string',
                'characters' => 'nullable|integer',
                'hint' => 'nullable|string|max:255'
            ];

            $itemRules = [
                'text' => 'required|string|max:80'
            ];

            $validatorModule = Validator::make($moduleNew, $moduleRules);
            if ($validatorModule->passes()) {

                foreach ($moduleNew['inputs'] as $input) {

                    $validatorInput = Validator::make($input, $inputRules);
                    if ($validatorInput->passes()) {

                        if ($input['inputType'] == 'spinner') {

                            foreach ($input['items'] as $item) {
                                $validatorItem = Validator::make($item, $itemRules);
                                if (!$validatorItem->passes()) {
                                    return response()->json([
                                        'error' => true,
                                        'message' => 'Items of spinner input are in bad format.',
                                    ], 400);
                                }
                            }
                        }
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => "Module's inputs are in bad format.",
                        ], 400);
                    }
                }
            } else {
                return response()->json([
                    'error' => true,
                    'message' => "Name is in bad format.",
                ], 400);
            }

            $module = Module::create(['name' => $moduleNew['name'], 'category_id' => $moduleNew['category_id'], 'territory_id' => $territory->id]);

            unset($module->territory_id, $module->created_at, $module->updated_at);

            $inputs = array();

            foreach ($moduleNew['inputs'] as $input) {

                $inp = Input::create([
                    'title' => $input['title'],
                    'inputType' => $input['inputType'],
                    'characters' => $input['characters'],
                    'hint' => $input['hint'],
                    'module_id' => $module->id]);

                unset($inp->module_id, $inp->created_at, $inp->updated_at);

                array_push($inputs, $inp);

                if ($inp->inputType == 'spinner') {

                    $items = array();

                    foreach ($input['items'] as $item) {
                        $itm = Item::create([
                            'text' => $item['text'],
                            'input_id' => $inp->id
                        ]);

                        unset($itm->input_id, $itm->created_at, $itm->updated_at);

                        array_push($items, $itm);
                    }
                    $inp->items = $items;
                }
            }

            $module->inputs = $inputs;

            return response()->json([
                'module' => $module,
            ], 200);

        }

        return abort('403');
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

        if ($territory->admin_id === Auth::id()) {

            $moduleNew = $request->module;

            $moduleRules = [
                'name' => 'required|string|max:80',
                'category_id' => 'required|integer'
            ];

            $inputRules = [
                'title' => 'required|string|max:80',
                'inputType' => 'required|string',
                'characters' => 'nullable|integer',
                'hint' => 'nullable|string|max:255'
            ];

            $itemRules = [
                'text' => 'required|string|max:80'
            ];

            $validatorModule = Validator::make($moduleNew, $moduleRules);
            if ($validatorModule->passes()) {

                foreach ($moduleNew['inputs'] as $input) {

                    $validatorInput = Validator::make($input, $inputRules);
                    if ($validatorInput->passes()) {

                        if ($input['inputType'] == 'spinner') {

                            foreach ($input['items'] as $item) {
                                $validatorItem = Validator::make($item, $itemRules);
                                if (!$validatorItem->passes()) {
                                    return response()->json([
                                        'error' => true,
                                        'message' => 'Items of spinner input are in bad format.',
                                    ], 400);
                                }
                            }
                        }
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => "Module's inputs are in bad format.",
                        ], 400);
                    }
                }
            } else {
                return response()->json([
                    'error' => true,
                    'message' => "Name is in bad format.",
                ], 400);
            }

            $oldInputs = $module->inputs()->get();

            foreach ($oldInputs as $input) $input->delete();

            $oldModuleData = $module->moduleData()->get();

            foreach ($oldModuleData as $moduleData) $moduleData->delete();

            $inputs = array();

            foreach ($moduleNew['inputs'] as $input) {

                $inp = Input::create([
                    'title' => $input['title'],
                    'module_id' => $module->id,
                    'inputType' => $input['inputType'],
                    'characters' => $input['characters'],
                    'hint' => $input['hint']]);

                unset($inp->module_id, $inp->created_at, $inp->updated_at);

                array_push($inputs, $inp);

                if ($inp->inputType == 'spinner') {

                    $items = array();

                    foreach ($input['items'] as $item) {
                        $itm = Item::create([
                            'text' => $item['text'],
                            'input_id' => $inp->id
                        ]);

                        unset($itm->input_id, $itm->created_at, $itm->updated_at);

                        array_push($items, $itm);
                    }
                    $inp->items = $items;
                }
            }

            $module->name = $moduleNew['name'];

            $module->category_id = $moduleNew['category_id'];

            $module->update();

            unset($module->territory_id, $module->created_at, $module->updated_at);

            $module->inputs = $inputs;

            return response()->json([
                'module' => $module,
            ], 200);

        }

        return abort('403');
    }

    /**
     * Activate module.
     *
     * @param Territory $territory
     * @param Module $module
     * @return void
     * @throws Exception
     */
    public function activate(Territory $territory, Module $module)
    {
        if ($territory->admin_id === Auth::id()) {

            $module->active = !$module->active;
            $module->update();

            return response()->json([
                'error' => false,
            ], 200);
        }

        return abort('403');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Territory $territory
     * @param Module $module
     * @return void
     * @throws Exception
     */
    public function destroy(Territory $territory, Module $module)
    {

        if ($territory->admin_id === Auth::id()) {

            $module->delete();

            return response()->json([
                'error' => false,
            ], 200);
        }

        return abort('403');
    }
}
