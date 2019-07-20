<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Input extends Model
{
    protected $fillable = [
        'title', 'inputType', 'characters', 'hint', 'module_id'
    ];

    // získání modulu, který vlastní vstup
    public function module() {
        return $this->belongsTo(Module::class);
    }

    // získání položek, které spadají pod vstup (pokud existují)
    public function item() {
        return $this->hasMany(Item::class);
    }

    // získání všech dat vsputu
    public function inputData() {
        return $this->hasMany(InputData::class);
    }
}
