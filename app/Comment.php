<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use Filterable;

    protected $fillable = [
        'text', 'user_id', 'report_id'
    ];

    // získání uživatele, který je autorem komentáře
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // získání podnětu, pod kterým se komentář nachází
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    // získání všech lajků, které patří komentáři
    public function commentLikes()
    {
        return $this->hasMany(CommentLike::class);
    }
}
