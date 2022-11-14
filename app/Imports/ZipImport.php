<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\Zip;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ZipImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    private $areas;

    public function __construct()
    {
        $this->areas = Area::select('id', 'area_code');
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $area = $this->areas->where('area_code', $row['area_code'])->first();
            Zip::create([
                "area_id" => $area->id ?? Null,
                "zip_code"  => $row['zip_code']
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
