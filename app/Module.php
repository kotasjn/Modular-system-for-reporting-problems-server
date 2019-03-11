<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name'
    ];

    public function moduleData() {
        return $this->hasMany(ModuleData::class);
    }

    public function activeModule() {
        return $this->hasMany(ActiveModule::class);
    }
}
