<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    public function recipient()
    {
        return $this->belongsTo(User::class);
    }
}
