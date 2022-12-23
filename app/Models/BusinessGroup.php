<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessGroup extends Model
{
    use HasFactory, SoftDeletes, Sluggable, SluggableScopeHelpers;

    protected $table = "business_groups";
    protected $guarded = ['id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'   => 'group',
                'onUpdate' => true,
            ]
        ];
    }

    public static function search($search){
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%'.$search.'%')
                ->orWhere('group', 'like', '%'.$search.'%');
    }

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }
}
