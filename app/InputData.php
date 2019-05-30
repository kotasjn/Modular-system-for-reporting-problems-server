<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InputData extends Model
{
    protected $fillable = [
        'input_id', 'module_data_id', 'data'
    ];

    public function moduleData() {
        return $this->belongsTo(ModuleData::class);
    }

    public function input() {
        return $this->belongsTo(Item::class);
    }
}
