<?php

namespace App\Imports;

use App\Models\Province;
use App\Models\Region;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProvinceImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    private $regions;

    public function __construct()
    {
        $this->regions = Region::select('id', 'name')->get();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $region = $this->regions->where('name', $row['region_name'])->first();
            Province::create([
                "region_id" => $region->id ?? Null,
                "name"      => $row['name'],
                "code"      => $row['code']
            ]);
        }
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
