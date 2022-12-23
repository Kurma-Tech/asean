<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatentCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "patent_categories";
    protected $guarded = ['id'];

    public static function search($search){
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%'.$search.'%')
                ->orWhere('classification_category', 'like', '%'.$search.'%')
                ->orWhere('ipc_code', 'like', '%'.$search.'%');
    }

    public function patents()
    {
        return $this->belongsToMany(Patent::class, 'patent_pivot_patent_category', 'category_id', 'patent_id')->withPivot('country_id');
    }

    public function parent(){
        return $this->hasOne(PatentCategory::class, "id", "parent_id");
    }
}
