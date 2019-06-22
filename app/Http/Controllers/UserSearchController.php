<?php

namespace App\Http\Controllers;

use App\Territory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSearchController extends Controller
{

    public function search(Request $request, Territory $territory)
    {
        if ($territory->admin_id === Auth::id()) {
            $users = User::search($request->email)->get(['id AS user_id', 'avatarURL', 'email', 'name']);

            return response()->json($users);
        } else {
            return abort(403);
        }
    }
}
