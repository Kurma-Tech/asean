<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zip extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "country_zips";
    protected $guarded = ['id'];

    public static function search($search){
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%'.$search.'%')
                ->orWhere('zip_code', 'like', '%'.$search.'%');
    }

    public function areas()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
