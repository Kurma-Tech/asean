<?php

namespace App\Imports;

use App\Models\Journal;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JournalImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    public function model(array $row)
    {
        if (isset($row['category_code'])) {
            $category = DB::table('journal_categories')
                ->select('id')
                ->where('acjs_code', $row['category_code'])
                ->first();
        } else {
            $category = null;
        }
        $country         = DB::table('countries')->select('id', 'short_code')->where('short_code', $row['country_short_code'])->first();
        $keywordsToArray = explode(',', $row['keywords']); // Keywords explode with ,
        $keywordsJson    = json_encode($keywordsToArray);  // Keywords to json
        $authorToArray   = explode(',', $row['author_name']); // Author name explode with ,
        $namesJson       = json_encode($authorToArray);       // Author names to json
        
        return new Journal([
            "title"          => $row['title'],
            "published_year" => $row['published_year'],
            "abstract"       => $row['abstract'],
            "author_name"    => $namesJson,
            "category_id"    => ($category != null) ? $category->id : null,
            "country_id"     => $country->id ?? NULL,
            "publisher_name" => $row['publisher_name'],
            "source_title"   => $row['source_title'],
            "issn_no"        => $row['issn_no'],
            "citition_no"    => $row['citition_no'],
            "keywords"       => $keywordsJson,
            "long"           => $row['long'],
            "lat"            => $row['lat']
        ]);
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
