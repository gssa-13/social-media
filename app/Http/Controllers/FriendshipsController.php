<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendshipsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  User  $recipient
     * @return \Illuminate\Http\Response
     */
    public function store(User $recipient)
    {
        $friendship = Friendship::firstOrCreate([
            'sender_id' => Auth::id(),
            'recipient_id' => $recipient->id
        ]);

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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $friendship = Friendship::where([
            'sender_id' => Auth::id(),
            'recipient_id' => $user->id
        ])->orWhere([
            'sender_id' => $user->id,
            'recipient_id' => Auth::id()
        ])->first();

        if ($friendship->status === 'denied') {
            return response()->json([
                'friendship_status' => 'denied'
            ]);
        }

        return response()->json([
            'friendship_status' => $friendship->delete() ? 'deleted' : ''
        ]);
    }
}
