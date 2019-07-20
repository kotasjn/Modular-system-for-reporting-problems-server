<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];

    // výběr podnětů spadajících pod kategorii
    public function report(){
        return $this->hasMany(Report::class);
    }

    // výběr řešitelů, kteří mají zodpovědnost za danou kategorii
    public function problemSolver() {
        return $this->hasMany(ProblemSolver::class);
    }

}
