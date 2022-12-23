<?php

namespace App\Imports;

use App\Models\JournalCategory;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JournalCategoryImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    public function model(array $row)
    {
        $journalCategory = null;
        $section_id     = null;
        $division_id    = null;
        $group_id       = null;
        $class_id       = null;

        if (isset($row['parent_name'])) {
            $journalCategory = JournalCategory::where('category', $row['parent_name'])
            ->select('id','parent_id','section_id','division_id','group_id')
            ->first();

            if (is_null($journalCategory->parent_id)) {
                $section_id  = $journalCategory->id;
            }
            if (!is_null($journalCategory->section_id)) {
                $section_id  = $journalCategory->section_id;
                $division_id = $journalCategory->id;
            }
            if (!is_null($journalCategory->division_id)) {
                $section_id  = $journalCategory->section_id;
                $division_id = $journalCategory->division_id;
                $group_id    = $journalCategory->id;
            }
            if (!is_null($journalCategory->group_id)) {
                $section_id  = $journalCategory->section_id;
                $division_id = $journalCategory->division_id;
                $group_id    = $journalCategory->group_id;
                $class_id    = $journalCategory->id;
            }
        }

        return new JournalCategory([
            "parent_id"   => ($journalCategory != null) ? $journalCategory->id : null,
            "section_id"  => ($section_id != null) ? $section_id : null,
            "division_id" => ($division_id != null) ? $division_id : null,
            "group_id"    => ($group_id != null) ? $group_id : null,
            "class_id"    => ($class_id != null) ? $class_id : null,
            "ajcs_code"   => $row['ajcs_code'] ?? null,
            "category"    => $row['category'] ?? null
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
