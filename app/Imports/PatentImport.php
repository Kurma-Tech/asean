<?php

namespace App\Imports;

use App\Models\Patent;
use App\Models\PatentCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PatentImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    private $patentCategories;

    public function __construct()
    {
        $this->patentCategories = PatentCategory::select('id', 'ipc_code')->get();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
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

            $codeToArray = array_map('trim', explode(';', $row['ipc_code']));    // Category name explode with ,
            
            if($codeToArray)
            {
                $category_collection = [];
                foreach ($codeToArray as $code)
                {
                    $country = ['country_id' => $country->id ?? NULL];
                    $categoryQuery = $this->patentCategories->where('ipc_code', $code);
                    if($categoryQuery->count() != 0){
                        $category = $categoryQuery->first() ?? Null;
                        $category_collection[$category->id] = $country;
                    }else{
                        continue;
                    }
                }
            }

            $date = explode('/', $row['filing_date']);

            Patent::create([
                "title"             => $row['title'],
                "filing_no"         => $row['filing_no'],
                "applicant_company" => $row['applicant_company'],
                "inventor_name"     => $inventorJson,
                "country_id"        => ($country != null) ? $country->id : null,
                "kind_id"           => ($patentKind != null) ? $patentKind->id : null,
                "type_id"           => ($patentType != null) ? $patentType->id : null,
                "registration_date" => $row['registration_date'],
                "registration_no"   => $row['registration_no'],
                "filing_date"       => $row['filing_date'],
                "publication_date"  => $row['publication_date'],
                "abstract"          => $row['abstract'],
                "long"              => $row['long'],
                "lat"               => $row['lat'],
                "month"             => $date[0],
                "year"              => $date[2],
                "month_and_year"    => ($date[0] != null && $date[2] != null) ? $date[2] ."-".$date[0] : null,
            ])->patentCategories()->sync($category_collection);
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
