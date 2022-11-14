<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "country_areas";
    protected $guarded = ['id'];

    public static function search($search){
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%'.$search.'%')
                ->orWhere('area_name', 'like', '%'.$search.'%')
                ->orWhere('area_code', 'like', '%'.$search.'%');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
