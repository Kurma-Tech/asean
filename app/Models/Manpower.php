<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manpower extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "manpowers";
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    public static function search($search){
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%'.$search.'%')
                ->orWhere('name', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%')
                ->orWhere('skilled', 'like', '%'.$search.'%');
    }

    public function classifications()
    {
        return $this->belongsToMany(IndustryClassification::class, 'classification_manpowers', 'manpower_id', 'classification_id')
                    ->withPivot(['seats']);
    }
}
