<?php

namespace App\Imports;

use App\Models\Business;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class BusinessImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    public function model(array $row)
    {
        $businessType = DB::table('business_types')->select('id', 'type')->where('type', $row['business_type'])->first();
        $industryClassification = DB::table('industry_classifications')->select('id', 'code')->where('code', $row['classification_code'])->first();
        $country = DB::table('countries')->select('id', 'short_code')->where('short_code', $row['country_short_code'])->first();
        $date = explode('/', $row['date_registered']);
        
        return new Business([
            "year"                       => $row['year'],
            "sec_no"                     => $row['sec_no'],
            "company_name"               => $row['company_name'],
            "date_registered"            => $row['date_registered'],
            "business_type_id"           => ($businessType != null) ? $businessType->id : null,
            "industry_classification_id" => ($industryClassification != null) ? $industryClassification->id : null,
            "country_id"                 => ($country != null) ? $country->id : null,
            "ngc_code"                   => $row['ngc_code'] ?? Null,
            "status"                     => $row['status'] ?? 'Registered',
            "address"                    => $row['address'] ?? Null,
            "industry_code"              => $row['industry_code'] ?? Null,
            "industry_description"       => $row['industry_description'] ?? "",
            "geo_code"                   => $row['geo_code'] ?? Null,
            "geo_description"            => $row['geo_description'] ?? Null,
            "lat"                        => $row['lat'],
            "long"                       => $row['long'],
            "month"                      => $date[0],
            "day"                        => $date[1],
            "month_and_year"             => $date[2]."-".$date[0]
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
