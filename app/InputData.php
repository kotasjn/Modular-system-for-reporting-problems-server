<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InputData extends Model
{
    protected $fillable = [
        'input_id', 'module_data_id', 'data'
    ];

    // získání objektu moduleData, kterému data vstupu patří
    public function moduleData() {
        return $this->belongsTo(ModuleData::class);
    }

    // získání vstupu, kterému data náleží
    public function input() {
        return $this->belongsTo(Item::class);
    }
}
