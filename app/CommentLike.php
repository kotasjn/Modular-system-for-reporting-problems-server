<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    protected $fillable = [
        'comment_id', 'user_id'
    ];

    // získání uživatele, který je autorem lajku
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // získání komentáře, který vlastní lajk
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
