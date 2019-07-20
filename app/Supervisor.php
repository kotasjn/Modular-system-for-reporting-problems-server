<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $fillable = [
        'territory_id', 'user_id'
    ];

    // získání uživatele, kterému náleži role supervizora
    public function user() {
        return $this->belongsTo(User::class);
    }

    // získání samosprávy, pod kterou je supervizor registrován
    public function territory() {
        return $this->belongsTo(Territory::class);
    }
}
