<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportPhoto extends Model
{
    protected $fillable = [
        'url', 'report_id'
    ];

    // získání podnětu, kterému fotografie náleží
    public function report() {
        return $this->belongsTo(Report::class);
    }
}
