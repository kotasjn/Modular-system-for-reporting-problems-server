<?php


namespace App\Http\Controllers\Api;


use App\ActiveModule;
use App\Territory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActiveModuleController
{
    public function index(Request $request, Territory $territory)
    {
        return response()->json([
            'error' => false,
            'modules' => ActiveModule::where('territory_id', $territory->id)->get(),
        ], 200);
    }

    public function store(Request $request, Territory $territory)
    {
        if($territory->admin_id == Auth::id()) {
            ActiveModule::create(['territory_id' => $territory->id, 'module_id' => $request->id]);

            return response()->json([
                'error' => false,
            ], 200);
        } else {
            return response()->json([
                'error' => true,
            ], 403);
        }
    }

    public function destroy(Territory $territory, ActiveModule $activeModule){
        if($territory->admin_id == Auth::id()){

            $activeModule->delete();

            return response()->json([
                'error' => false,
            ], 200);

        } else {
            return response()->json([
                'error' => true,
            ], 403);
        }
    }
}
