<?php

namespace App\Http\Controllers;

use App\Territory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSearchController extends Controller
{

    /**
     * Vyhledávání uživatelů pro účely přidání nového zaměstnance
     *
     * @param Request $request
     * @param Territory $territory
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function search(Request $request, Territory $territory)
    {
        // vyhledávat může pouze admnistrátor samosprávy
        if ($territory->admin_id === Auth::id()) {

            // vyhledání uživatele podle emailové adresy
            $users = User::where("email", $request->email)->get(['id AS user_id', 'avatarURL', 'email', 'name']);

            return response()->json($users);
        } else {
            return abort(403);
        }
    }
}
