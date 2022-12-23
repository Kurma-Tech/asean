<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "cities";
    protected $guarded = ['id'];

    public static function search($search){
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%'.$search.'%')
                ->orWhere('name', 'like', '%'.$search.'%')
                ->orWhere('code', 'like', '%'.$search.'%');
    }

    public function districts()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }
}
