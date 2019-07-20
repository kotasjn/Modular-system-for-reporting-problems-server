<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Territory extends Model
{

    protected $fillable = [
        'name', 'avatarURL', 'admin_id', 'approver_id'
    ];

    // získání administrátora samosprávy
    public function admin() {
        return $this->belongsTo(User::class);
    }

    // získání schvalovatele samosprávy
    public function approver() {
        return $this->belongsTo(User::class);
    }

    // získání supervizora samosprávy
    public function supervisor() {
        return $this->hasMany(Supervisor::class);
    }

    // získání řešitele samosprávy
    public function problemSolver() {
        return $this->hasMany(ProblemSolver::class);
    }

    // získání podnětů samosprávy
    public function reports() {
        return $this->hasMany(Report::class);
    }

    // získání modulů samosprávy
    public function modules() {
        return $this->hasMany(Module::class);
    }
}
