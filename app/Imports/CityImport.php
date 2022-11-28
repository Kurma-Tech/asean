<?php

namespace App\Imports;

use App\Models\District;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CityImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    private $districts;

    public function __construct()
    {
        $this->districts = District::select('id', 'name')->get();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $district = $this->districts->where('name', $row['district_name'])->first();
            District::create([
                "district_id" => $district->id ?? Null,
                "name"        => $row['name'],
                "code"        => $row['code']
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
