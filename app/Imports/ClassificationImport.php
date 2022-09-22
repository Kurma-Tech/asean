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
    // private $industryClassifications;

    public function __construct()
    {
        // $this->industryClassifications = DB::table('industry_classifications')->select('id')->where('parent_id', null);
    }

    public function model(array $row)
    {

        if (isset($row['parent_name'])) {

            $industryClassification = DB::table('industry_classifications')
                ->select('id')
                ->where('parent_id', null)
                ->where('classifications', $row['parent_name'])
                ->first();
        } else {
            $industryClassification = null;
        }
        Log:info(($industryClassification != null) ? $industryClassification->id : $row['parent_name']);
        return new IndustryClassification([
            "parent_id"       => ($industryClassification != null) ? $industryClassification->id : null,
            "classifications" => $row['classifications'] ?? null,
            "psic_code"       => $row['psic_code'] ?? null,
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
