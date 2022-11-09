<?php

namespace App\Imports;

use App\Models\IndustryClassification;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ClassificationImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    public function model(array $row)
    {
        $classification = null;
        $section_id     = null;
        $division_id    = null;
        $group_id       = null;
        $class_id       = null;

        if (isset($row['parent_code'])) {
            $classification = IndustryClassification::where('code', $row['parent_code'])
            ->select('id','parent_id','section_id','division_id','group_id')
            ->first();

            if (is_null($classification->parent_id)) {
                $section_id  = $classification->id;
            }
            if (!is_null($classification->section_id)) {
                $section_id  = $classification->section_id;
                $division_id = $classification->id;
            }
            if (!is_null($classification->division_id)) {
                $section_id  = $classification->section_id;
                $division_id = $classification->division_id;
                $group_id    = $classification->id;
            }
            if (!is_null($classification->group_id)) {
                $section_id  = $classification->section_id;
                $division_id = $classification->division_id;
                $group_id    = $classification->group_id;
                $class_id    = $classification->id;
                
            }
        }
        
        return new IndustryClassification([
            "parent_id"       => ($classification != null) ? $classification->id : null,
            "section_id"      => ($section_id != null) ? $section_id : null,
            "division_id"     => ($division_id != null) ? $division_id : null,
            "group_id"        => ($group_id != null) ? $group_id : null,
            "class_id"        => ($class_id != null) ? $class_id : null,
            "classifications" => $row['classifications'] ?? null,
            "code"            => $row['code'] ?? null,
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
