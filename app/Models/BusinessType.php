<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessType extends Model
{
    use HasFactory, SoftDeletes, sluggable, SluggableScopeHelpers;

    protected $table = "business_types";
    protected $guarded = ['id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'   => 'type',
                'onUpdate' => true,
            ]
        ];
    }

    public static function search($search){
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%'.$search.'%')
                ->orWhere('type', 'like', '%'.$search.'%');
    }

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }
}
