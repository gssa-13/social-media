<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcceptFriendshipsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('friendships.index', [
            'friendshipRequests' => Auth::user()->friendshipRequestsReceived
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Models\User  $sender
     * @return \Illuminate\Http\Response
     */
    public function store(User $sender)
    {
        Auth::user()->acceptFriendRequestFrom($sender);

        return response()->json([
            'friendship_status' => 'accepted'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $sender
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $sender)
    {
        Auth::user()->denyFriendRequestFrom($sender);

        return response()->json([
            'friendship_status' => 'denied'
        ]);
    }
}
