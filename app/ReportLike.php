<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportLike extends Model
{
    protected $fillable = [
        'user_id', 'report_id'
    ];

    public function report() {
        return $this->belongsTo(Report::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
