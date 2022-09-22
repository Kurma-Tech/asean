<?php

namespace App\Imports;

use App\Models\IndustryClassification;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ClassificationImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    private $industryClassifications;

    public function __construct()
    {
        $this->industryClassifications = DB::table('industry_classifications')->select('id', 'classifications', 'parent_id')->where('parent_id', Null);
    }

    public function model(array $row)
    {
        $industryClassification = $this->industryClassifications->where('classifications', $row['parent_name'])->first();
        return new IndustryClassification([
            "parent_id"       => $industryClassification->id ?? null,
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
