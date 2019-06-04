<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name', 'active', 'category_id', 'territory_id'
    ];

    public function territory() {
        return $this->belongsTo(Territory::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function moduleData() {
        return $this->hasMany(ModuleData::class);
    }
}
