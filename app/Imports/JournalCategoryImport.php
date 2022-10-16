<?php

namespace App\Imports;

use App\Models\JournalCategory;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JournalCategoryImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    public function model(array $row)
    {
        if (isset($row['parent_name'])) {

            $journalCategory = DB::table('journal_categories')
                ->select('id')
                ->where('parent_id', null)
                ->where('category', $row['parent_name'])
                ->first();
        } else {
            $journalCategory = null;
        }
        
        return new JournalCategory([
            "parent_id"       => ($journalCategory != null) ? $journalCategory->id : null,
            "category" => $row['category'] ?? null,
            "acjs_code"       => $row['acjs_code'] ?? null,
        ]);
    }

    public function batchSize(): int
    {
        return 300;
    }

    public function chunkSize(): int
    {
        return 300;
    }
}