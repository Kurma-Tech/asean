<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "journal_categories";
    protected $guarded = ['id'];

    public static function search($search){
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%'.$search.'%')
                ->orWhere('category', 'like', '%'.$search.'%')
                ->orWhere('acjs_code', 'like', '%'.$search.'%');
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    public function parent(){
        return $this->hasOne(JournalCategory::class, "id", "parent_id");
    }
}