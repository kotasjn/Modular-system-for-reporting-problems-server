<?php

namespace App;

use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use SpatialTrait;

    protected $fillable = [
        'title', 'state', 'userNote', 'location', 'user_id', 'category_id', 'territory_id','employeeNote', 'address'
    ];

    protected $spatialFields = [
        'location'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function supervisor()
    {
        return $this->hasMany(Supervisor::class);
    }

    public function responsible()
    {
        return $this->hasOne(User::class);
    }

    public function category()
    {
        return $this->hasOne(Category::class);
    }

    public function reportPhoto()
    {
        return $this->hasMany(ReportPhoto::class);
    }

    public function moduleData()
    {
        return $this->hasOne(ModuleData::class);
    }

    public static function findReportsByDistance($point, $numberOfRecords)
    {
        return Report::orderByDistance('location', $point, $direction = 'asc')->limit($numberOfRecords);
    }

    public static function findReportsByDistanceFromPoint($point, $skippedRecords, $numberOfRecords)
    {
        return Report::orderByDistance('location', $point, $direction = 'asc')->skip($skippedRecords)->limit($numberOfRecords);
    }

}
