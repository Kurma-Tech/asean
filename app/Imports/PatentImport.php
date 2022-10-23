<?php

namespace App\Imports;

use App\Models\Country;
use App\Models\Patent;
use App\Models\PatentCategory;
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
        $this->patentType     = PatentType::select('id')->get();
        $this->patentKind     = PatentKind::select('id')->get();
        $this->country        = Country::select('id', 'short_code')->get();
    }

    public function model(array $row)
    {
        $patentType     = $this->patentType->where('id', $row['ip_type_id'])->first();
        $patentKind     = $this->patentKind->where('id', $row['ip_kind_id'])->first();
        $country        = $this->country->where('short_code', $row['country_short_code'])->first();

        $inventorToArray = explode(',', $row['inventor_name']);
        $inventorJson = json_encode($inventorToArray);
        
        return new Patent([
            "title"             => $row['title'],
            "filing_no"         => $row['filing_no'],
            "applicant_company" => $row['applicant_company'],
            "inventor_name"     => $inventorJson,
            "country_id"        => $country->id ?? NULL,
            "kind_id"           => $patentKind->id ?? NULL,
            "type_id"           => $patentType->id ?? NULL,
            "category_id"       => $row['ipc_code'] ? json_encode($row['ipc_code']) : NULL,
            "registration_date" => $row['registration_date'],
            "registration_no"   => $row['registration_no'],
            "filing_date"       => $row['filing_date'],
            "publication_date"  => $row['publication_date'],
            "abstract"          => $row['abstract'],
            "long"              => $row['long'],
            "lat"               => $row['lat'],
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
