<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProblemSolver extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'territory_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function territory() {
        return $this->belongsTo(Territory::class);
    }
}
