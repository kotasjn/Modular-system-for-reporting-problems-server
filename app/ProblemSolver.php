<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProblemSolver extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'territory_id'
    ];

    // získání uživatele, kterému patří role řešitele
    public function user() {
        return $this->belongsTo(User::class);
    }

    // získání kategorie, za kterou má řešitel zodpovědnost
    public function category() {
        return $this->belongsTo(Category::class);
    }

    // získání samosprávy, ve které řešitel pracuje
    public function territory() {
        return $this->belongsTo(Territory::class);
    }
}
