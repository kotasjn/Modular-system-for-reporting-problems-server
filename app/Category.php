<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];

    public function report(){
        return $this->hasMany(Report::class);
    }

    public function problemSolver() {
        return $this->hasMany(ProblemSolver::class);
    }

}
