<?php

namespace App\Imports;

use App\Models\IndustryClassification;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ClassificationImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    public function model(array $row)
    {
        return new IndustryClassification([
            "parent_id"       => $row['parent_id'],
            "classifications" => $row['classifications'],
            "psic_code"       => $row['psic_code'],
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
