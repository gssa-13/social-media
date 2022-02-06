<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusLikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function store(Status $status)
    {
        $status->like();

        return response()->json([
            'likes_count' => $status->likesCount()
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        $status->unlike();

        return response()->json([
            'likes_count' => $status->likesCount()
        ]);
    }
}
