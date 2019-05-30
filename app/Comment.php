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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function commentLikes() {
        return $this->hasMany(CommentLike::class);
    }
}
