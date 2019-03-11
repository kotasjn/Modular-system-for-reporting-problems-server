<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $fillable = [
        'territory_id', 'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function territory() {
        return $this->belongsTo(Territory::class);
    }
}
