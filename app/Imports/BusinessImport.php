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
        if (isset($row['business_type'])) {
            $businessType = DB::table('business_types')
                ->select('id', 'type')
                ->where('type', $row['business_type'])
                ->first();
        } else {
            $businessType = null;
        }
        if (isset($row['business_group'])) {
            $businessGroup = DB::table('business_groups')
                ->select('id', 'group')
                ->where('group', $row['business_group'])
                ->first();
        } else {
            $businessGroup = null;
        }
        if (isset($row['classification_code'])) {
            $industryClassification = DB::table('industry_classifications')
                ->select('id', 'code', 'class_id')
                ->where('code', $row['classification_code'])
                ->first();
        } else {
            $industryClassification = null;
        }
        if (isset($row['country_short_code'])) {
            $country = DB::table('countries')
                ->select('id', 'short_code')
                ->where('short_code', $row['country_short_code'])
                ->first();
        } else {
            $country = null;
        }
        if (isset($row['region_name'])) {
            $region = DB::table('regions')
                ->select('id', 'name')
                ->where('name', $row['region_name'])
                ->first();
        } else {
            $region = null;
        }
        if (isset($row['province_name'])) {
            $province = DB::table('provinces')
                ->select('id', 'name')
                ->where('name', $row['province_name'])
                ->first();
        } else {
            $province = null;
        }
        if (isset($row['district_name'])) {
            $district = DB::table('districts')
                ->select('id', 'name')
                ->where('name', $row['district_name'])
                ->first();
        } else {
            $district = null;
        }
        if (isset($row['city_name'])) {
            $city = DB::table('cities')
                ->select('id', 'name')
                ->where('name', $row['city_name'])
                ->first();
        } else {
            $city = null;
        }
        
        $date = explode('/', $row['date_registered']);
        
        return new Business([
            "year"                       => $row['year'],
            "sec_no"                     => $row['sec_no'],
            "company_name"               => $row['company_name'],
            "date_registered"            => $row['date_registered'],
            "business_type_id"           => ($businessType != null) ? $businessType->id : null,
            "group_id"                   => ($businessGroup != null) ? $businessGroup->id : null,
            "industry_classification_id" => ($industryClassification != null) ? $industryClassification->id : null,
            "country_id"                 => ($country != null) ? $country->id : null,
            "region_id"                  => ($region != null) ? $region->id : null,
            "province_id"                => ($province != null) ? $province->id : null,
            "district_id"                => ($district != null) ? $district->id : null,
            "city_id"                    => ($city != null) ? $city->id : null,
            "ngc_code"                   => $row['ngc_code'] ?? Null,
            "status"                     => $row['status'] ?? 'Registered',
            "address"                    => $row['address'] ?? Null,
            "industry_code"              => $row['industry_code'] ?? Null,
            "industry_description"       => $row['industry_description'] ?? "",
            "geo_code"                   => $row['geo_code'] ?? Null,
            "geo_description"            => $row['geo_description'] ?? Null,
            "lat"                        => $row['lat'],
            "long"                       => $row['long'],
            "month"                      => $date[0] ?? Null,
            "day"                        => $date[1] ?? Null,
            "month_and_year"             => ($date[0] != null && $date[2] != null) ? $date[2] ."-".$date[0] : null,
            "parent_classification_id"   => ($industryClassification != null) ? $industryClassification->class_id : null,
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
