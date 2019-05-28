<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        unset($user->isSuperAdmin);
        unset($user->isEmployee);
        unset($user->isSupervisor);
        unset($user->created_at);
        unset($user->updated_at);
        return response()->json(
            $user
        );
    }
}
