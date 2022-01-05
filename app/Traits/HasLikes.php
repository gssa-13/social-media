<?php

namespace App\Traits;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;

trait HasLikes
{
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function like()
    {
        $this->likes()->firstOrCreate([
            'user_id' => Auth::id()
        ]);
    }

    public function unlike()
    {
        $this->likes()->where([ 'user_id' => Auth::id() ])->delete();
    }

    public function isLiked()
    {
        return $this->likes()->where('user_id', Auth::id() )->exists();
    }

    public function likesCount()
    {
        return $this->likes()->count();
    }
}
