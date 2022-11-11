<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "journals";
    protected $guarded = ['id'];

    public static function search($search){
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%'.$search.'%')
                ->orWhere('title', 'like', '%'.$search.'%')
                ->orWhere('abstract', 'like', '%'.$search.'%')
                ->orWhere('publisher_name', 'like', '%'.$search.'%')
                ->orWhere('issn_no', 'like', '%'.$search.'%')
                ->orWhere('cited_score', 'like', '%'.$search.'%')
                ->orWhere('link', 'like', '%'.$search.'%')
                ->orWhere('published_year', 'like', '%'.$search.'%')
                ->orWhere('source_title', 'like', '%'.$search.'%')
                ->orWhere('author_name', 'like', '%'.$search.'%');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function journalCategories()
    {
        return $this->belongsToMany(JournalCategory::class, 'journal_pivot_journal_category', 'journal_id', 'category_id')->withPivot('country_id');
    }
}