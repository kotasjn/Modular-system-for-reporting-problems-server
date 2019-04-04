<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    protected $fillable = [
        'comment_id', 'user_id'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function comment()
    {
        return $this->hasOne(Comment::class);
    }
}
