<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleData extends Model
{
    protected $fillable = [
        'report_id', 'module_id'
    ];

    // získání podnětu, který vlastní data modulu
    public function report() {
        return $this->belongsTo(Report::class);
    }

    // získání modulu, kterému data patří
    public function module() {
        return $this->belongsTo(Module::class);
    }

    // získání dat, které náleží vstupu
    public function inputData() {
        return $this->hasMany(InputData::class);
    }
}
