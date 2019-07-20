<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticable
{
    use Notifiable, HasApiTokens;

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

    // získat podněty, kterých je uživatel autorem
    public function owner() {
        return $this->hasMany(Report::class,'user_id');
    }

    // získat podněty, za které je uživatel zodpovědný
    public function responsible() {
        return $this->hasMany(Report::class,'responsible_user_id');
    }

    // získat řešitele, kterými uživatel je (pokud má uživatel přiřazenou tuto roli)
    public function problemSolver() {
        return $this->hasOne(ProblemSolver::class);
    }

    // získat všechny role supervisorů, které uživatel vlastní (pokud má uživatel přiřazenou tuto roli)
    public function supervisor() {
        return $this->hasMany(Supervisor::class);
    }

    // získat role schvalovatelů, kterými uživatel je (pokud má uživatel přiřazenou tuto roli)
    public function approver() {
        return $this->hasMany(Territory::class);
    }

    // získat role administrátoru, které patří uživateli (pokud má uživatel přiřazenou tuto roli)
    public function admin() {
        return $this->hasMany(Territory::class);
    }

    // získat seznam lajků, které uživatel podnětům rozdal
    public function reportLike() {
        return $this->hasMany(ReportLike::class);
    }

    // získat seznam komentářů, které uživatel napsal
    public function comments() {
        return $this->hasMany(Comment::class);
    }

    // získat seznam lajků komentářů, které uživatel rozdal
    public function commentLikes() {
        return $this->hasMany(CommentLike::class);
    }
}
