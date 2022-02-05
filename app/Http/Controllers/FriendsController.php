<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{
    public function __invoke()
    {
        return view('friends.index', [
            'friends' => Auth::user()->friends()
        ]);
    }
}
