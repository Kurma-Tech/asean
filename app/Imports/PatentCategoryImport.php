<?php

namespace App\Imports;

use App\Models\PatentCategory;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PatentCategoryImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    public function model(array $row)
    {
        if (isset($row['parent_ipc_code'])) {
            $patentCategory = DB::table('patent_categories')
                ->select('id')
                ->where('ipc_code', $row['parent_ipc_code'])
                ->first();
        } else {
            $patentCategory = null;
        }
        
        return new PatentCategory([
            "parent_id"               => ($patentCategory != null) ? $patentCategory->id : null,
            "classification_category" => $row['category_title'] ?? null,
            "ipc_code"                => $row['ipc_code'] ?? null,
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
