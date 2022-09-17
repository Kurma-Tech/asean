<?php

namespace App\Imports;

use App\Models\Business;
use App\Models\BusinessType;
use App\Models\IndustryClassification;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class BusinessImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    private $businessType;
    private $industryClassification;

    public function __construct()
    {
        $this->businessType = BusinessType::select('id', 'type')->get();
        $this->industryClassification = IndustryClassification::select('id', 'psic_code')->get();
    }

    public function model(array $row)
    {
        $businessType = $this->businessType->where('type', $row['business_type'])->first();
        $industryClassification = $this->industryClassification->where('psic_code', $row['psic_code'])->first();
        
        return new Business([
            "year" => $row['year'],
            "sec_no" => $row['sec_no'],
            "company_name" => $row['company_name'],
            "date_registered" => $row['date_registered'],
            "business_type_id" => $businessType->id ?? NULL,
            "industry_classification_id" => $industryClassification->id ?? NULL,
            "ngc_code" => $row['ngc_code'],
            "status" => $row['status'],
            "address" => $row['address'],
            "industry_code" => $row['industry_code'],
            "industry_description" => $row['industry_description'],
            "geo_code" => $row['geo_code'],
            "geo_description" => $row['geo_description'],
            "lat" => $row['lat'],
            "long" => $row['long']
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
