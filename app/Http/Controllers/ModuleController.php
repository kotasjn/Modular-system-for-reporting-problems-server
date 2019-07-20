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
     * Získání seznamu modulů samosprávy
     *
     * @param Territory $territory
     * @return Response
     */
    public function index(Territory $territory)
    {

        // spravovat moduly může pouze administrátor obce
        if ($territory->admin_id === Auth::id()) {

            $modules = $territory->modules()->get();

            // každému modulu je nutné přiřadit vstupy, které mu náleží
            foreach ($modules as $module) {
                unset($module->territory_id, $module->created_at, $module->updated_at);

                // výběr vstupů pro modul
                $module->inputs = Input::where('module_id', $module->id)->get();

                foreach ($module->inputs as $input) {
                    unset($input->module_id, $input->created_at, $input->updated_at);

                    // pokud je vstup selectbox a má tak položky seznamu, je nutné je také dodat
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
     * Uložení modulu do databáze
     *
     * @param Request $request
     * @param Territory $territory
     * @return void
     */
    public function store(Request $request, Territory $territory)
    {
        // ukládat nové moduly může pouze administrátor samosprávy
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

            // je nutné validovat, zda modul splňuje všechny podmínky pro jeho uložení do databáze

            $validatorModule = Validator::make($moduleNew, $moduleRules);
            if ($validatorModule->passes()) {

                foreach ($moduleNew['inputs'] as $input) {

                    // stejně jako u modulů i u vstupů je nutné validovat, zda mají všechny náležitosti
                    $validatorInput = Validator::make($input, $inputRules);
                    if ($validatorInput->passes()) {

                        if ($input['inputType'] == 'spinner') {

                            foreach ($input['items'] as $item) {
                                // stejně tak se validují i položky selectboxu
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

            // vytvoření záznamu modulu v databázi
            $module = Module::create(['name' => $moduleNew['name'], 'category_id' => $moduleNew['category_id'], 'territory_id' => $territory->id]);

            unset($module->territory_id, $module->created_at, $module->updated_at);

            $inputs = array();

            foreach ($moduleNew['inputs'] as $input) {

                // vytvoření záznamu vstupu pro konkrétní modul v databázi
                $inp = Input::create([
                    'title' => $input['title'],
                    'inputType' => $input['inputType'],
                    'characters' => $input['characters'],
                    'hint' => $input['hint'],
                    'module_id' => $module->id]);

                unset($inp->module_id, $inp->created_at, $inp->updated_at);

                array_push($inputs, $inp);

                // pokud je typ vstupu spinner/selectbox je nutné vytvořit i záznamy pro položky seznamu
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
     * Zobrazení konkrétního modulu
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
     * Aktualizace modulu v databázi
     *
     * @param Request $request
     * @param Territory $territory
     * @param Module $module
     * @return void
     */
    public function update(Request $request, Territory $territory, Module $module)
    {

        // upravovat moduly může pouze administrátor
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

            // je nutné validovat, zda modul splňuje všechny podmínky pro jeho uložení do databáze
            $validatorModule = Validator::make($moduleNew, $moduleRules);
            if ($validatorModule->passes()) {

                foreach ($moduleNew['inputs'] as $input) {

                    // validace vstupů
                    $validatorInput = Validator::make($input, $inputRules);
                    if ($validatorInput->passes()) {

                        if ($input['inputType'] == 'spinner') {

                            // validace položek seznamu
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

            // nejdříve se musí odstranit staré vstupy
            $oldInputs = $module->inputs()->get();

            foreach ($oldInputs as $input) $input->delete();

            // poté se odstraní stará data modulů
            $oldModuleData = $module->moduleData()->get();

            foreach ($oldModuleData as $moduleData) $moduleData->delete();

            $inputs = array();

            // vytvoření nových vstupů modulu
            foreach ($moduleNew['inputs'] as $input) {

                $inp = Input::create([
                    'title' => $input['title'],
                    'module_id' => $module->id,
                    'inputType' => $input['inputType'],
                    'characters' => $input['characters'],
                    'hint' => $input['hint']]);

                unset($inp->module_id, $inp->created_at, $inp->updated_at);

                array_push($inputs, $inp);

                // pokud je typ vstupu spinner/selectbox je třeba vytvořit i položky seznamu
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

            // aktualizace původního modulu
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
     * Aktivace modulu
     *
     * @param Territory $territory
     * @param Module $module
     * @return void
     * @throws Exception
     */
    public function activate(Territory $territory, Module $module)
    {
        // aktivaci může provést pouze administrátor obce
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
     * Odstranění modulu z databáze
     *
     * @param Territory $territory
     * @param Module $module
     * @return void
     * @throws Exception
     */
    public function destroy(Territory $territory, Module $module)
    {
        // odstranění modulu může provést pouze administrátor obce
        if ($territory->admin_id === Auth::id()) {

            $module->delete();

            return response()->json([
                'error' => false,
            ], 200);
        }

        return abort('403');
    }
}
