<?php

namespace App\Http\Controllers\Api;

use App\Filters\ReportFilters;
use App\Http\Controllers\Controller;
use App\Report;
use App\ReportPhoto;
use Grimzy\LaravelMysqlSpatial\Types\Point;
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
     * nepovinne skippedRecords, user
     *
     * @param Request $request
     * @param ReportFilters $filters
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ReportFilters $filters)
    {
        // Latitude and longitude are mandatory
        if (!$request->has('location')) {
            return response()->json([
                'error' => true,
                'message' => 'Location is mandatory.',
            ], 400);
        }

        $location = new Point($request->input('location.coordinates.0'), $request->input('location.coordinates.1'));

        $reports = Report::filter($filters)->orderByDistance('location', $location, 'asc')->limit(5)->get();

        foreach ($reports as $report) {

            $point1 = 'POINT(' . $report->location->getLat() . ', ' . $report->location->getLng() . ')';
            $point2 = 'POINT(' . $location->getLat() . ', ' . $location->getLng() . ')';

            $value = DB::select('SELECT ST_Distance( ' . $point1 . ', ' . $point2 . ') AS distance');

            if ($value != null)
                $report->distance = $value[0]->distance;
            else
                $report->distance = null;

            $photos = ReportPhoto::select('url')->where('report_id', '=', $report->id)->get();

            $arrayPhotos = array();
            for($i = 0; $i < count($photos); $i++) {
                array_push($arrayPhotos, json_decode($photos[$i])->url);
            }
            $report->photos = $arrayPhotos;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $location = new Point($request->input('location.coordinates.0'), $request->input('location.coordinates.1'));

        // TODO zjisteni, zda se bod nachazi na danem uzemi (zatim pevne dane terr = 0)
        Report::create(array_merge($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'state' => ['required', 'integer'],
            'category_id' => ['required', 'integer']
        ]), ['location' => $location, 'user_id' => Auth::id(), 'territory_id' => 0]));

        return response()->json([
            'error' => false,
        ], 200);
    }

    /**
     * @param Report $report
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Report $report)
    {
        return response()->json([
            'report' => $report
        ]);
    }

    /**
     * @param Request $request
     * @param Report $report
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Report $report)
    {
        // user can delete only his reports
        if ($report->user()->id == Auth::id()) {
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
