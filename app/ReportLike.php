<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportLike extends Model
{
    protected $fillable = [
        'user_id', 'report_id'
    ];

    // xískání podnětu, kterému lajk náleží
    public function report() {
        return $this->belongsTo(Report::class);
    }

    // získání uživatele, který lajk přidal
    public function user() {
        return $this->belongsTo(User::class);
    }
}
