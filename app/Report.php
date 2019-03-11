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

    public function supervisor() {
        return $this->hasMany(Supervisor::class);
    }

    public function responsible() {
        return $this->hasOne(User::class);
    }

    public function category() {
        return $this->hasOne(Category::class);
    }

    public function reportPhoto() {
        return $this->hasMany(ReportPhoto::class);
    }

    public function moduleData() {
        return $this->hasOne(ModuleData::class);
    }

}
