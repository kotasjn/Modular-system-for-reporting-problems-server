<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleData extends Model
{
    protected $fillable = [
        'data', 'report_id', 'module_id'
    ];

    public function report() {
        return $this->belongsTo(Report::class);
    }

    public function module() {
        return $this->belongsTo(Module::class);
    }
}
