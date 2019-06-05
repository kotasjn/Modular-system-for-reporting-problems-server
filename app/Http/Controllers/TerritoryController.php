<?php

namespace App\Http\Controllers;

use App\Supervisor;
use App\Territory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;

class TerritoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Territory $territory)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Territory $territory
     * @return \Illuminate\Http\Response
     */
    public function show(Territory $territory)
    {
        if ($territory->admin() === Auth::user() || $territory->approver() === Auth::user() || $territory->supervisor()->where('user_id', Auth::id())) {
            return view('dashboard', compact('territory'));
        } else { return abort('403'); }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Territory $territory
     * @return \Illuminate\Http\Response
     */
    public function edit(Territory $territory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Territory $territory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Territory $territory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Territory $territory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Territory $territory)
    {
        //
    }
}
