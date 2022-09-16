<?php

namespace App\Imports;

use App\Models\PatentKind;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PatentKindImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    public function model(array $row)
    {
        $slug = SlugService::createSlug(PatentKind::class, 'slug', $row['kind']);
        return new PatentKind([
            "kind" => $row['kind'],
            "slug" => $slug
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
