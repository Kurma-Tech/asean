<?php

namespace App\Imports;

use App\Models\District;
use App\Models\Province;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DistrictImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    private $provinces;

    public function __construct()
    {
        $this->provinces = Province::select('id', 'code');
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $province = $this->provinces->where('code', $row['province_code'])->first();
            District::create([
                "province_id" => $province->id ?? Null,
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
