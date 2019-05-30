<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'input_id', 'text'
    ];

    public function input() {
        return $this->belongsTo(Input::class);
    }
}
