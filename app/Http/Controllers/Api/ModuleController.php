<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Input;
use App\Item;
use App\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{

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

        $modules = Module::where('territory_id', $territory)->where('active', true)->where('category_id', $request->input('category_id'))->get();

        foreach ($modules as $m) {
            unset($m->created_at, $m->updated_at, $m->active, $m->territory_id, $m->category_id);

            $m->inputs = Input::where('module_id', $m->id)->get();

            foreach ($m->inputs as $input) {
                unset($input->module_id, $input->created_at, $input->updated_at);
                $input->items = Item::where('input_id', $input->id)->get();

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