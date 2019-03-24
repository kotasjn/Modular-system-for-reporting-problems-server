<?php

namespace App;

use App\Filters\Filterable;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Report extends Model
{
    use SpatialTrait;
    use Filterable;

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
}
