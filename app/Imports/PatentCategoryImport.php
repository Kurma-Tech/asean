<?php

namespace App\Imports;

use App\Models\PatentCategory;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PatentCategoryImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    public function model(array $row)
    {
        if (isset($row['section_ipc_code'])) {
            $sectionCategory = DB::table('patent_categories')
                ->select('id')
                ->where('ipc_code', $row['section_ipc_code'])
                ->first();
        } else {
            $sectionCategory = null;
        }

        if (isset($row['division_ipc_code'])) {
            $divisionCategory = DB::table('patent_categories')
                ->select('id')
                ->where('ipc_code', $row['division_ipc_code'])
                ->first();
        } else {
            $divisionCategory = null;
        }

        if (isset($row['group_ipc_code'])) {
            $groupCategory = DB::table('patent_categories')
                ->select('id')
                ->where('ipc_code', $row['group_ipc_code'])
                ->first();
        } else {
            $groupCategory = null;
        }

        if (isset($row['class_ipc_code'])) {
            $classCategory = DB::table('patent_categories')
                ->select('id')
                ->where('ipc_code', $row['class_ipc_code'])
                ->first();
        } else {
            $classCategory = null;
        }
        
        return new PatentCategory([
            "section_id"              => ($sectionCategory != null) ? $sectionCategory->id : null,
            "division_id"             => ($divisionCategory != null) ? $divisionCategory->id : null,
            "group_id"                => ($groupCategory != null) ? $groupCategory->id : null,
            "class_id"                => ($classCategory != null) ? $classCategory->id : null,
            "classification_category" => $row['category_title'] ?? null,
            "ipc_code"                => $row['ipc_code'] ?? null,
        ]);
    }

    public function batchSize(): int
    {
        return 300;
    }

    public function chunkSize(): int
    {
        return 300;
    }
}
