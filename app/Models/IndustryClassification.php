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
                'source'   => 'classifications',
                'onUpdate' => true,
            ]
        ];
    }

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }
}
