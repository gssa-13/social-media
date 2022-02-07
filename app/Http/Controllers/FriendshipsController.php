<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendshipsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  User  $recipient
     * @return \Illuminate\Http\Response
     */
    public function store(User $recipient)
    {
        if (Auth::id() === $recipient->id) {
            abort(400);
        }

        $friendship = Auth::user()->sendFriendRequestTo($recipient);

        return response()->json([
            'friendship_status' => $friendship->fresh()->status
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $recipient)
    {
        $friendship = Friendship::betweenUsers(Auth::user(), $recipient)->first();

        return response()->json([
            'friendship_status' => $friendship->status
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $friendship = Friendship::betweenUsers(Auth::user(), $user)->first();

        if ($friendship->status === 'denied' && Auth::id() === (int)$friendship->sender_id ) {
            return response()->json([
                'friendship_status' => 'denied'
            ]);
        }

        return response()->json([
            'friendship_status' => $friendship->delete() ? 'deleted' : ''
        ]);
    }
}
