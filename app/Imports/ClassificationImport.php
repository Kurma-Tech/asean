<?php

namespace App\Imports;

use App\Models\IndustryClassification;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ClassificationImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    public function model(array $row)
    {

        if (isset($row['parent_name'])) {

            $industryClassification = DB::table('industry_classifications')
                ->select('id')
                ->where('classifications', $row['parent_name'])
                ->first();
        } else {
            $industryClassification = null;
        }
        
        return new IndustryClassification([
            "parent_id"       => ($industryClassification != null) ? $industryClassification->id : null,
            "classifications" => $row['classifications'] ?? null,
            "code"            => $row['code'] ?? null,
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
