<?php


namespace App\Http\Controllers\Api;

use App\Comment;
use App\Filters\CommentFilters;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController
{
    public function index(Report $report, CommentFilters $filters)
    {
        return response()->json([
            'error' => false,
            'comments' => Comment::filter($filters)->where('report_id', $report->id)->limit(10)->get(),
        ], 200);
    }

    public function show(Report $report, Comment $comment)
    {
        return response()->json([
            'error' => false,
            'comment' => Comment::find($comment->id),
        ], 200);
    }

    public function store(Request $request, Report $report)
    {
        Comment::create(array_merge($request->validate([
            'text' => 'required|string|max:511',
        ]), ['user_id' => Auth::id(), 'report_id' => $report->id]));

        return response()->json([
            'error' => false,
        ], 200);
    }

    public function update(Request $request, Report $report, Comment $comment){

        if($comment->user_id == Auth::id()){

            $comment->update(array_merge($request->validate([
                'text' => 'required|string|max:511',
            ]), ['user_id' => Auth::id(), 'report_id' => $report->id]));

            return response()->json([
                'error' => false,
            ], 200);

        } else {
            return response()->json([
                'error' => true,
            ], 403);
        }
    }

    public function destroy(Report $report, Comment $comment){
        if($comment->user_id == Auth::id()){

            $comment->delete();

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