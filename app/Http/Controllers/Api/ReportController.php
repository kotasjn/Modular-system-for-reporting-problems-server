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
     * Vybere se z databaze určitý počet podnětů, který odpovídá velikosti stránky komponenty ViewPager v mobilní aplikaci
     * nebo se použije implicitní hodnota. Na report se použijí filtry požadované uživatelem jako například id vlastníka
     * nebo stav podnětu. Filtry se použijí i k určení přesné stránky ViewPageru respektive kolik stran se má přeskočit.
     *
     * @param Request $request
     * @param ReportFilters $filters
     * @return JsonResponse
     */
    public function reports(Request $request, ReportFilters $filters)
    {
        // Latitude a longitude jsou povinné
        if (!$request->has('lat') || !$request->has('lng')) {
            return response()->json([
                'error' => true,
                'message' => 'Location (lat and lng parameters) is mandatory.',
            ], 400);
        }

        // výběr podnětů s uplatněním filtrů
        $reports = Report::filter($filters)->limit(($request->has('page_size')) ? $request->input('page_size') : config('app.page_size'))->get();

        foreach ($reports as $report) {

            // přidání url adres fotografií podnětu
            $photos = ReportPhoto::select('url')->where('report_id', '=', $report->id)->get();

            $arrayPhotos = array();
            for ($i = 0; $i < count($photos); $i++) {
                array_push($arrayPhotos, json_decode($photos[$i])->url);
            }
            $report->photos = $arrayPhotos;

            // přidání komentářů
            $report->comments = Comment::where('report_id', $report->id)->count();

            // přidání lajků
            $report->likes = ReportLike::where('report_id', $report->id)->count();

            // určení, zda přihlášený uživatel podnět lajknul
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
     * Uložení nového podnětu do databáze. V rámci zpracování podnětu dojde i k uložení ostatních objektů včetně
     * dat modulů.
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

        // uložení url adres fotografií uložených na cloudu
        foreach ($request->photos as $url) {
            $photo = new ReportPhoto([
                'url' => $url,
                'report_id' => $report->id
            ]);

            $photo->save();
        }

        // pokud podnět obsahuje data modulů, musí se zpracovat
        if($request->moduleData != null) {
            foreach ($request->moduleData as $dataModule) {

                $moduleData = new ModuleData([
                    'module_id' => $dataModule["module_id"],
                    'report_id' => $report->id
                ]);
                $moduleData->save();

                // všechny hodnoty vstupů se musí uložit zvlášť
                foreach ($dataModule["inputData"] as $dataInput) {

                    $inputData = new InputData([
                        'module_data_id' => $moduleData->id,
                        'input_id' => $dataInput["input_id"],
                        'data' => $dataInput["value"]
                    ]);
                    $inputData->save();
                }
            }
        }

        return response()->json([
            'error' => false,
        ], 200);
    }

    /**
     * Zobrazení detailu podnětu
     *
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
     * Aktualizace pozměněného podnětu
     *
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
     * Odstranění podnětu z databáze
     *
     * @param Report $report
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Report $report)
    {
        // podněty může mazat pouze jejich vlastník (v rámci mobilního API)
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
