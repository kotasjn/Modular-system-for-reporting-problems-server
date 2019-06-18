<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'telephone', 'avatarURL',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function owner() {
        return $this->hasMany(Report::class,'user_id');
    }

    public function responsible() {
        return $this->hasMany(Report::class,'responsible_user_id');
    }

    public function problemSolver() {
        return $this->hasOne(ProblemSolver::class);
    }

    public function supervisor() {
        return $this->hasMany(Supervisor::class);
    }

    public function approver() {
        return $this->hasMany(Territory::class);
    }

    public function admin() {
        return $this->hasMany(Territory::class);
    }

    public function reportLike() {
        return $this->hasMany(ReportLike::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function commentLikes() {
        return $this->hasMany(CommentLike::class);
    }
}
