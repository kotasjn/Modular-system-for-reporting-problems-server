<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\CommentLike;
use App\Filters\CommentFilters;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController
{
    /**
     * Seznam komentářů pod podnětem
     *
     * @param Report $report
     * @param CommentFilters $filters
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Report $report, CommentFilters $filters)
    {
        $comments = Comment::filter($filters)->where('report_id', $report->id)->limit(10)->get();

        foreach ($comments as $comment) {
            $comment->likes = CommentLike::where('comment_id', $comment->id)->count();
            $comment->userLike = (CommentLike::where('user_id', Auth::id())->where('comment_id', $comment->id)->first() === null) ? false : true;
        }

        return response()->json([
            'error' => false,
            'comments' => $comments,
        ], 200);
    }

    /**
     * Zobrazení konkrétního komentáře
     *
     * @param Report $report
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Report $report, Comment $comment)
    {
        $comment->likes = CommentLike::where('comment_id', $comment->id)->count();
        $comment->userLike = (CommentLike::where('user_id', Auth::id())->where('comment_id', $comment->id)->first() != null) ? true : false;

        return response()->json([
            'error' => false,
            'comment' => $comment,
        ], 200);
    }

    /**
     * Uložení komentáře do databáze
     *
     * @param Request $request
     * @param Report $report
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Report $report)
    {
        Comment::create(array_merge($request->validate([
            'text' => 'required|string|max:511',
        ]), ['user_id' => Auth::id(), 'report_id' => $report->id]));

        return response()->json([
            'error' => false,
        ], 200);
    }

    /**
     * Uložení upraveného komentáře
     *
     * @param Request $request
     * @param Report $report
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Odstranění komentáře z databáze
     *
     * @param Report $report
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
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