<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "businesses";
    protected $guarded = ['id'];

    public static function search($search){
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%'.$search.'%')
                ->orWhere('company_name', 'like', '%'.$search.'%');
    }

    public static function filter($filter){
        return empty($filter) ? static::query()
            : static::query()->where('company_name', 'like', '%'.$filter.'%');
    }

    public function industryClassification()
    {
        return $this->belongsTo(IndustryClassification::class);
    }

    public function businessType()
    {
        return $this->belongsTo(BusinessType::class);
    }
}
