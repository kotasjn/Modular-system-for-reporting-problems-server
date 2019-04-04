<?php


namespace App\Http\Controllers\Api;


use App\Report;
use App\ReportLike;
use Illuminate\Support\Facades\Auth;

class ReportLikeController
{
    public function index(Report $report)
    {
        return response()->json([
            'error' => false,
            'likes' => ReportLike::where('report_id', $report->id)->count(),
        ], 200);
    }

    public function store(Report $report)
    {
        ReportLike::create(['user_id' => Auth::id(), 'report_id' => $report->id]);

        return response()->json([
            'error' => false,
        ], 200);
    }

    public function show()
    {
        abort(404);
    }

    public function update()
    {
        abort(404);
    }

    public function destroy(Report $report, ReportLike $like)
    {
        if ($like->user_id == Auth::id()) {

            $like->delete();

            return response()->json([
                'error' => false,
            ], 200);

        } else {
            return response()->json([
                'error' => true,
            ], 403);
        }
    }
}