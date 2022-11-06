<?php

namespace App\Imports;

use App\Models\Country;
use App\Models\Patent;
use App\Models\PatentCategory;
use App\Models\PatentKind;
use App\Models\PatentType;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PatentImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    public function model(array $row)
    {
        if (isset($row['ip_type_name'])) {
            $patentType = DB::table('patent_types')
                ->select('id')
                ->where('type', $row['ip_type_name'])
                ->first();
        } else {
            $patentType = null;
        }

        if (isset($row['ip_kind_name'])) {
            $patentKind = DB::table('patent_kinds')
                ->select('id')
                ->where('kind', $row['ip_kind_name'])
                ->first();
        } else {
            $patentKind = null;
        }

        if (isset($row['country_short_code'])) {
            $country = DB::table('countries')
                ->select('id')
                ->where('short_code', $row['country_short_code'])
                ->first();
        } else {
            $country = null;
        }

        $inventorToArray = explode(';', $row['inventor_name']);
        $inventorJson = json_encode($inventorToArray);

        $codeToArray = explode(';', $row['ipc_code']);
        $codeJson = json_encode($codeToArray);
        
        return new Patent([
            "title"             => $row['title'],
            "filing_no"         => $row['filing_no'],
            "applicant_company" => $row['applicant_company'],
            "inventor_name"     => $inventorJson,
            "country_id"        => ($patentType != null) ? $patentType->id : null,
            "kind_id"           => ($patentKind != null) ? $patentKind->id : null,
            "type_id"           => ($country != null) ? $country->id : null,
            "category_id"       => $codeJson,
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
