<?php

namespace App\Imports;

use App\Models\IndustryClassification;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ClassificationImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{

    public function model(array $row)
    {

        return new IndustryClassification([
            "parent_id"       => $row['parent_id'] ?? null,
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
