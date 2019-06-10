<?php

namespace App\Http\Controllers;

use App\ReportPhoto;
use App\Territory;
use Exception;
use Illuminate\Http\Request;
use App\Report;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Territory $territory
     * @return Response
     */
    public function index(Territory $territory)
    {
        if ($territory->admin_id === Auth::id() || $territory->approver_id === Auth::id() || $territory->supervisor()->where('user_id', Auth::id())->first() || $territory->problemSolver()->where('user_id', Auth::id())->first()) {
            return response()->json([
                "reports" => Report::where('territory_id', $territory->id)->get()
            ], 200);
        } else {
            return abort('403');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param Territory $territory
     * @param Report $report
     * @return void
     */
    public function show(Territory $territory, Report $report)
    {

        if ($territory->admin_id === Auth::id() || $territory->approver_id === Auth::id() || $territory->supervisor()->where('user_id', Auth::id())->first() || $territory->problemSolver()->where('user_id', Auth::id())->first()) {

            $photos = ReportPhoto::select('url')->where('report_id', '=', $report->id)->get();

            $arrayPhotos = array();
            for ($i = 0; $i < count($photos); $i++) {
                array_push($arrayPhotos, json_decode($photos[$i])->url);
            }
            $report->photos = $arrayPhotos;

            return response()->json([
                "report" => $report
            ], 200);
        } else {
            return abort('403');
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param Territory $territory
     * @param Report $report
     * @return void
     */
    public function update(Territory $territory, Report $report)
    {

        if ($territory->admin_id === Auth::id() || $territory->approver_id === Auth::id() || $territory->problemSolver()->where('user_id', Auth::id())->where('user_id', $report->responsible_user_id)->first()) {

            $report->update(array_merge(request()->validate([
                'title' => ['required', 'string', 'max:255'],
                'userNote' => ['required', 'string', 'max:255'],
                'employeeNote' => ['required', 'string', 'max:255'],
                'category_id' => ['required', 'integer'],
            ])));

            return response()->json([
                "report" => $report
            ], 200);

        } else {
            return abort('403');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Territory $territory
     * @param Report $report
     * @return void
     * @throws Exception
     */
    public function destroy(Territory $territory, Report $report)
    {
        if ($territory->admin_id === Auth::id() || $territory->approver_id === Auth::id() || $territory->problemSolver()->where('user_id', Auth::id())->where('user_id', $report->responsible_user_id)->first()) {

            $report->delete();

            return response()->json([
                "error" => false
            ], 200);

        } else {
            return abort('403');
        }
    }
}
