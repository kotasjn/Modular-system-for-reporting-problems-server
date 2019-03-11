<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActiveModule extends Model
{
    protected $fillable = [
        'territory_id', 'module_id'
    ];

    public function module() {
        return $this->belongsTo(Module::class);
    }

    public function territory() {
        return $this->belongsTo(Territory::class);
    }
}
