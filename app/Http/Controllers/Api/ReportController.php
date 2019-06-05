<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\Filters\ReportFilters;
use App\Http\Controllers\Controller;
use App\InputData;
use App\ModuleData;
use App\Report;
use App\ReportLike;
use App\ReportPhoto;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    /**
     * vybrat z db okolni podnety v okruhu souradnic, ktere jsou predany v requestu, podle vzdalenosti
     * vystupem bude kolekce 5 hlaseni, ktere jsou odeslany skrz JSON zpet
     * v pripade, ze uzivatel chce zobrazit vetsi pocet hlaseni nez predanych 5, posle se v requestu
     * pocet aktualne obdrzenych podnetu a vybere se dalsich 5
     * povinne location
     * nepovinne skip, user
     *
     * @param Request $request
     * @param ReportFilters $filters
     * @return JsonResponse
     */
    public function reports(Request $request, ReportFilters $filters)
    {
        // Latitude and longitude are mandatory
        if (!$request->has('lat') || !$request->has('lng')) {
            return response()->json([
                'error' => true,
                'message' => 'Location (lat and lng parameters) is mandatory.',
            ], 400);
        }

        $location = new Point($request->input('lat'), $request->input('lng'));
        $point1 = 'POINT(' . $location->getLat() . ', ' . $location->getLng() . ')';

        $reports = Report::filter($filters)->orderByDistance('location', $location, 'asc')->limit(($request->has('page_size')) ? $request->input('page_size') : config('app.page_size'))->get();

        foreach ($reports as $report) {

            $point2 = 'POINT(' . $report->location->getLat() . ', ' . $report->location->getLng() . ')';
            $value = DB::select('SELECT ST_Distance( ' . $point1 . ', ' . $point2 . ') AS distance');

            if ($value != null)
                $report->distance = $value[0]->distance;
            else
                $report->distance = null;

            $photos = ReportPhoto::select('url')->where('report_id', '=', $report->id)->get();

            $arrayPhotos = array();
            for ($i = 0; $i < count($photos); $i++) {
                array_push($arrayPhotos, json_decode($photos[$i])->url);
            }
            $report->photos = $arrayPhotos;
            $report->comments = Comment::where('report_id', $report->id)->count();
            $report->likes = ReportLike::where('report_id', $report->id)->count();
            $report->userLike = (ReportLike::where('report_id', $report->id)->where('user_id', Auth::id())->first()) ? true : false;

            $report->location = (object)[
                'lat' => $report->location->getLat(),
                'lng' => $report->location->getLng(),
            ];
        }

        return response()->json([
            'error' => false,
            'reports' => $reports,
        ], 200);
    }

    /**
     * Prijmu data (title, state, userNote, employeeNote, address, location)
     * Vytvorim objekt Report, zvaliduju vstupni hodnoty a vytvorim zaznam v db.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {

        $location = new Point($request->input('location.lat'), $request->input('location.lng'));

        $report = Report::create(array_merge($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'userNote' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer']
        ]), ['location' => $location, 'user_id' => Auth::id()]));


        foreach ($request->photos as $url) {
            $photo = new ReportPhoto([
                'url' => $url,
                'report_id' => $report->id
            ]);

            $photo->save();
        }

        foreach ($request->moduleData as $dataModule) {

            $moduleData = new ModuleData([
                'module_id' => $dataModule["module_id"],
                'report_id' => $report->id
            ]);
            $moduleData->save();

            foreach ($dataModule["inputData"] as $dataInput) {

                $inputData = new InputData([
                    'module_data_id' => $moduleData->id,
                    'input_id' => $dataInput["input_id"],
                    'data' => $dataInput["value"]
                ]);
                $inputData->save();
            }
        }


        return response()->json([
            'error' => false,
        ], 200);
    }

    /**
     * @param Report $report
     * @return JsonResponse
     */
    public function show(Report $report)
    {

        $report->comments = Comment::where('report_id', $report->id)->count();

        return response()->json([
            'report' => $report
        ]);
    }

    /**
     * @param Request $request
     * @param Report $report
     * @return JsonResponse
     */
    public function update(Request $request, Report $report)
    {
        $report->update(array_merge($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'state' => ['required', 'integer'],
            'location' => ['required']
        ])));

        return response()->json([
            'error' => false,
        ], 200);
    }

    /**
     * @param Report $report
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Report $report)
    {
        // user can delete only his reports
        if ($report->user->id == Auth::id()) {
            $report->delete();

            return response()->json([
                'error' => false,
            ], 200);
        }

        return response()->json([
            'error' => true,
            'message' => 'Unauthenticated'
        ], 403);
    }
}
