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
        $parentCategory = null;
        $section_id     = null;
        $division_id    = null;
        $group_id       = null;

        if (isset($row['parent_ipc_code'])) {
            $parentCategory = PatentCategory::where('ipc_code', $row['parent_ipc_code'])
            ->select('id','section_id','division_id','group_id')
            ->first();

            if (!is_null($parentCategory->section_id)) {
                $section_id  = $parentCategory->section_id;
            }
            if (!is_null($parentCategory->division_id)) {
                $division_id = $parentCategory->division_id;
            }
            if (!is_null($parentCategory->group_id)) {
                $group_id    = $parentCategory->group_id;
            }
        }
        
        return new PatentCategory([
            "parent_id"               => ($parentCategory != null) ? $parentCategory->id : null,
            "section_id"              => ($section_id != null) ? $section_id : null,
            "division_id"             => ($division_id != null) ? $division_id : null,
            "group_id"                => ($group_id != null) ? $group_id : null,
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
