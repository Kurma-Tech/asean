<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndustryClassification extends Model
{
    use HasFactory, SoftDeletes, Sluggable, SluggableScopeHelpers;

    protected $table = "industry_classifications";
    protected $guarded = ['id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'   => ['classifications'],
                'onUpdate' => true,
                'unique'   => true,
            ]
        ];
    }

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
        return $this->belongsToMany(Manpower::class, 'classification_manpowers');
    }
}
