<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Input;
use App\Item;
use App\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{

    /**
     * Získání aktivovaných modulů pro danou samosprávu. Zatím se vybírají pouze moduly u jediné samosprávy. V budoucnu
     * by mělo být možné samosprávu určit podle souřadnic zaslaných uživatelem.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActiveModules(Request $request)
    {
        if(!$request->has('lat')) {
            return response()->json([
                'error' => true,
                'message' => 'Latitude is mandatory.',
            ], 400);
        } else if (!$request->has('lng')) {
            return response()->json([
                'error' => true,
                'message' => 'Longitude is mandatory.',
            ], 400);
        }else if (!$request->has('category_id')) {
            return response()->json([
                'error' => true,
                'message' => 'Category ID is mandatory.',
            ], 400);
        }

        // TODO v budoucnu vyhledat territory pomoci souradnic
        $territory = 1;

        // výběr všech aktivních modulů pro danou samosprávy
        $modules = Module::where('territory_id', $territory)->where('active', true)->where('category_id', $request->input('category_id'))->get();

        // ke každému modulu je nutno dohledat vstupy, které obsahuje
        foreach ($modules as $m) {
            unset($m->created_at, $m->updated_at, $m->active, $m->territory_id, $m->category_id);

            // vyhledání všech vstupů
            $m->inputs = Input::where('module_id', $m->id)->get();

            // nyní je nutné projít všechny vstupy, odebrat zbytečné atributy a vyhledat případné položky seznamu
            foreach ($m->inputs as $input) {
                unset($input->module_id, $input->created_at, $input->updated_at);

                // vyhledání položek seznamu
                $input->items = Item::where('input_id', $input->id)->get();

                // odstranění zbytečných atributů u položek seznamu
                foreach ($input->items as $item)
                    unset($item->input_id, $item->created_at, $item->updated_at);
            }
        }

        return response()->json([
            'error' => false,
            'modules' => $modules,
        ], 200);

    }
}