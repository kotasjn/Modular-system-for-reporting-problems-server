<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use test\Mockery\ReturnTypeObjectTypeHint;

class Territory extends Model
{
    protected $fillable = [
        'name', 'avatarURL', 'admin_id', 'approver_id'
    ];

    public function admin() {
        return $this->belongsTo(User::class);
    }

    public function approver() {
        return $this->belongsTo(User::class);
    }

    public function supervisor() {
        return $this->hasMany(Supervisor::class);
    }

    public function problemSolver() {
        return $this->hasMany(ProblemSolver::class);
    }

    public function report() {
        return $this->hasMany(Report::class);
    }

    public function activeModule() {
        return $this->hasMany(ActiveModule::class);
    }
}
