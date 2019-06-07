<?php

namespace App\Http\Controllers;

use App\Territory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
}
