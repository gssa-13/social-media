<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentLikesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * @param  App\Models\Comment  $comment
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Comment $comment, Request $request)
    {
        $comment->like();

        return response()->json([
            'likes_count' => $comment->likesCount()
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy( Comment $comment)
    {
        $comment->unlike();

        return response()->json([
            'likes_count' => $comment->likesCount()
        ]);
    }
}
