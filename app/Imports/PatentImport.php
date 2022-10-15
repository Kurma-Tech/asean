<?php

namespace App\Imports;

use App\Models\Country;
use App\Models\Patent;
use App\Models\PatentKind;
use App\Models\PatentType;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PatentImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    private $patentType;
    private $patentKind;
    private $country;

    public function __construct()
    {
        $this->patentType = PatentType::select('id')->get();
        $this->patentKind = PatentKind::select('id')->get();
        $this->country    = Country::select('id', 'short_code')->get();
    }

    public function model(array $row)
    {
        $patentType = $this->patentType->where('id', $row['type_id'])->first();
        $patentKind = $this->patentKind->where('id', $row['kind_id'])->first();
        $country    = $this->country->where('id', $row['country_short_code'])->first();
        
        return new Patent([
            "title"      => $row['title'],
            "patent_id"  => $row['patent_id'],
            "country_id" => $country->id ?? NULL,
            "kind_id"    => $patentKind->id ?? NULL,
            "type_id"    => $patentType->id ?? NULL,
            "date"       => $row['date'],
            "long"       => $row['long'],
            "lat"        => $row['lat'],
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
