<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Report;
use Grimzy\LaravelMysqlSpatial\Types\Geometry;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{

    /**
     * vybrat z db okolni podnety v okruhu souradnic, ktere jsou predany v URL, podle vzdalenosti
     * vystupem bude kolekce 15 nebo klientem nahlaseneho poctu hlaseni, ktere jsou odeslany skrz JSON zpet
     * v pripade, ze uzivatel chce zobrazit vetsi pocet hlaseni nez predanych 15, posle se v requestu
     * ID posledniho podnetu v kolekce (ten ma nejvetsi vzdalenost od vychoziho bodu) a vybere se 15
     * dalsich podnetu, ktere maji vetsi vzdalenost nez dany bod, ale zaroven nejmensi vzdalenost od vychoziho bodu
     * povinne lat, ln
     * nepovinne report_id, numberOfRecords
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        // Latitude and longitude are mandatory
        if (!$request->has('lat') || !$request->has('lng')) {
            return response()->json([
                'error' => true,
                'message' => 'Missing lat and lng attributes!',
            ], 400);
        }

        // Creating Point object from given coordinates
        $point = new Point($request->lat, $request->lng);

        // If request contains 'skippedRecords' means that we want to load more reports. Client wants to get
        // collection of reports which has bigger distance from given coordinates. So we need to skip some amount of found records.

        if ($request->has('skippedRecords')) {

            $skippedRecords = $request->skippedRecords;

            // findReportsByDistanceFromPoint(point, skippedRecords, numberOfRecords) finds and sort reports by their distance from given location
            $reports = Report::orderByDistance('location', $point, $direction = 'asc')->skip($skippedRecords)->limit(($request->has('$request->numberOfRecords')) ? $request->numberOfRecords : 15);
        } else {
            // findReportsByDistance(point, numberOfRecords) finds and sort reports by their distance from given location
            $reports = Report::orderByDistance('location', $point, $direction = 'asc')->limit(($request->has('$request->numberOfRecords')) ? $request->numberOfRecords : 15);
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

        //$location = Geometry::fromJson(json_encode($request->location));

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
        $report->delete();

        return response()->json([
            'error' => false,
        ], 200);
    }
}
