<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "patents";
    protected $guarded = ['id'];

    public static function search($search){
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%'.$search.'%')
                ->orWhere('name', 'like', '%'.$search.'%')
                ->orWhere('c_code', 'like', '%'.$search.'%')
                ->orWhere('short_code', 'like', '%'.$search.'%');
    }

    public function patentType()
    {
        return $this->belongsTo(PatentType::class, 'type_id', 'id');
    }

    public function patentKind()
    {
        return $this->belongsTo(PatentKind::class, 'kind_id', 'id');
    }

    public function patentCategories()
    {
        return $this->belongsToMany(PatentCategory::class, 'patent_pivot_patent_category', 'patent_id', 'category_id')->withPivot('country_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
