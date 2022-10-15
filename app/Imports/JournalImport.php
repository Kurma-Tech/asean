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
        $category = DB::table('journal_categories')->select('id', 'acjs_code')->where('acjs_code', $row['acjs_code'])->first();
        $country = DB::table('countries')->select('id', 'short_code')->where('short_code', $row['country_short_code'])->first();
        
        return new Journal([
            "title"          => $row['title'],
            "published_year" => $row['published_year'],
            "abstract"       => $row['abstract'],
            "author_name"    => $row['author_name'],
            "category_id"    => $category->id ?? NULL,
            "country_id"     => $country->id ?? NULL,
            "publisher_name" => $row['publisher_name'],
            "source_title"   => $row['source_title'],
            "issn_no"        => $row['issn_no'],
            "citition_no"    => $row['citition_no'],
            "eid_no"         => $row['eid_no'],
            "keywords"       => $row['keywords'],
            "link"           => $row['link'],
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
