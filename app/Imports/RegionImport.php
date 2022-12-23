<?php

namespace App\Imports;

use App\Models\Region;
use App\Models\Country;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RegionImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    private $countries;

    public function __construct()
    {
        $this->countries = Country::select('id', 'short_code')->get();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $country = $this->countries->where('short_code', $row['country_short_code'])->first();
            Region::create([
                "country_id" => $country->id ?? Null,
                "name"  => $row['name'],
                "code"  => $row['code']
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
