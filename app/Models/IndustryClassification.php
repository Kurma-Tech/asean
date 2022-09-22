<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndustryClassification extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "industry_classifications";
    protected $guarded = ['id'];

    public static function search($search){
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%'.$search.'%')
                ->orWhere('classifications', 'like', '%'.$search.'%')
                ->orWhere('psic_code', 'like', '%'.$search.'%');
    }

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }

    public function parent(){
        return $this->hasOne(IndustryClassification::class, "id", "parent_id");
    }

    public function manpowers()
    {
        return $this->belongsToMany(Manpower::class, 'classification_manpowers', 'classification_id', 'manpower_id')
                    ->withPivot(['seats']);
    }
}
