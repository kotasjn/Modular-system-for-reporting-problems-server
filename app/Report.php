<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
      'title', 'state', 'latitude', 'longitude', 'userNote', 'employeeNote', 'address'
    ];

    public function user() {
        return $this->hasOne(User::class);
    }

    public function responsible() {
        return $this->hasOne(User::class);
    }

    public function category() {
        return $this->hasOne(Category::class);
    }



}
