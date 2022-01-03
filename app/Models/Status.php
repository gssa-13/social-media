<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Status extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function like()
    {
        $this->likes()->firstOrCreate([
            'user_id' => Auth::id()
        ]);
    }

    public function isLiked()
    {
        return $this->likes()->where('user_id', Auth::id() )->exists();
    }

    public function unlike()
    {
        $this->likes()->where([ 'user_id' => Auth::id() ])->delete();
    }

    public function likesCount()
    {
        return $this->likes()->count();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
