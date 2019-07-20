<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name', 'active', 'category_id', 'territory_id'
    ];

    // získání samosprávy, které modul náleží
    public function territory() {
        return $this->belongsTo(Territory::class);
    }

    // získání kategorie, které je modul přiřazen
    public function category() {
        return $this->belongsTo(Category::class);
    }

    // získání dat modulu
    public function moduleData() {
        return $this->hasMany(ModuleData::class);
    }

    // získání vstupů, které náleží modulu
    public function inputs() {
        return $this->hasMany(Input::class);
    }
}
