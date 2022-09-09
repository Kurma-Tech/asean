<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatentKind extends Model
{
    use HasFactory, SoftDeletes, sluggable, SluggableScopeHelpers;

    protected $table = "patent_kinds";
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'   => 'kind',
                'onUpdate' => true,
            ]
        ];
    }

    public static function search($search){
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%'.$search.'%')
                ->orWhere('kind', 'like', '%'.$search.'%');
    }
}
