<?php

namespace App;

use App\Filters\Filterable;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use SpatialTrait;
    use Filterable;

    protected $fillable = [
        'title', 'state', 'userNote', 'location', 'user_id', 'category_id', 'territory_id','employeeNote', 'address', 'responsible_user_id'
    ];

    // spatial atributy je nutné přidat do proměnné $spatialFields
    protected $spatialFields = [
        'location'
    ];

    // získání uživatele, který je autorem podnětu
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor()
    {
        return $this->hasMany(Supervisor::class);
    }

    // získání zodpovědné osoby za podnět (pokud je někomu přiřazen)
    public function responsible()
    {
        return $this->belongsTo(User::class);
    }

    // získání kategorie podnětu
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // získání fotografií, které náleží podnětu
    public function reportPhoto()
    {
        return $this->hasMany(ReportPhoto::class);
    }

    // získání dat modulů, které patří podnětu
    public function moduleData()
    {
        return $this->hasMany(ModuleData::class);
    }

    // získání komentářů pod podnětem
    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
