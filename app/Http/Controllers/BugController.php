<?php

namespace App\Http\Controllers;

use App\Bug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BugController extends Controller
{

    public function index(Request $request) {

        if(Auth::user()->isSuperAdmin) {

            return response()->json([
                'bugs' => DB::table('bugs')->select('id', 'description', 'created_at')->get(),
            ], 200);

        } else {
            return response()->json([
                'error' => true,
            ], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $bug = $request->bug;

        $bugRules = [
            'description' => 'required|string|max:255'
        ];

        $validatorModule = Validator::make($bug, $bugRules);
        if ($validatorModule->passes()) {

            Bug::create(['description' => $bug['description']]);

        } else {
            return response()->json([
                'error' => true,
            ], 400);
        }

        return response()->json([
            'error' => false,
        ], 200);
    }
}
