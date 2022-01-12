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
        $friendshipRequests = Friendship::with('sender')
            ->where('recipient_id', Auth::id())->get();

        return view('friendships.index', compact('friendshipRequests'));
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
     * @param  App\Models\User  $sender
     * @return \Illuminate\Http\Response
     */
    public function store(User $sender)
    {
        Friendship::where([
            'sender_id' => $sender->id,
            'recipient_id' =>  Auth::id(),
        ])->update([ 'status' => 'accepted' ]);

        return response()->json([
            'friendship_status' => 'accepted'
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
     * @param  User  $sender
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $sender)
    {
        Friendship::where([
            'sender_id' => $sender->id,
            'recipient_id' =>  Auth::id(),
        ])->update([ 'status' => 'denied' ]);

        return response()->json([
            'friendship_status' => 'denied'
        ]);
    }
}
