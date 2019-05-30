<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Input extends Model
{
    protected $fillable = [
        'title', 'inputType', 'characters', 'hint', 'module_id'
    ];

    public function module() {
        return $this->belongsTo(Module::class);
    }

    public function item() {
        return $this->hasMany(Item::class);
    }

    public function inputData() {
        return $this->hasMany(InputData::class);
    }
}
