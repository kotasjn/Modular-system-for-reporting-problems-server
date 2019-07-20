<?php


namespace App\Http\Controllers\Api;

use App\ModuleData;
use App\Report;
use Illuminate\Http\Request;

class   ModuleDataController
{
    /**
     * Uložení dat modulů
     *
     * @param Request $request
     * @param Report $report
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Report $report)
    {
        if(!$request->has('module')){
            return response()->json([
                'error' => true,
                'message' => 'Module id is mandatory.',
            ], 400);
        } else if (!$request->has('data')) {
            return response()->json([
                'error' => true,
                'message' => 'Module data are mandatory.',
            ], 400);
        }

        ModuleData::create(array_merge($request->validate([
            'data' => 'required|string|max:2047',
        ]), ['module_id' => $request->module, 'report_id' => $report->id]));

        return response()->json([
            'error' => false,
        ], 200);
    }
}