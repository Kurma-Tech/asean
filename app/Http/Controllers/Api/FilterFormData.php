<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessGroup;
use App\Models\BusinessType;
use App\Models\Country;
use App\Models\IndustryClassification;
use App\Models\JournalCategory;
use App\Models\PatentCategory;
use App\Models\PatentKind;
use App\Models\PatentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FilterFormData extends Controller
{
    public function getFilterFormData(Request $request) {
        $validator = Validator::make($request->all(), [
            'dataType' => 'required|string'
        ]);

        $data = $validator->validated();

        $categories = [];
        $business_groups = [];
        $business_types = [];
        $years = [];
        $patent_kinds = [];
        $patent_types = [];

        $countries = Cache::rememberForever('countries_with_ids', function () {
            return json_decode(DB::table('countries')->select('id', 'name')->where("status", "1")->get(), true);
        });

        if($data["dataType"] == 'business') {
            $categories = Cache::rememberForever('industry_classifications_with_ids', function () {
                return IndustryClassification::select('id', 'classifications')->where('parent_id', '!=', null)->where('classifications', '!=', null)->where('section_id', '!=', null)->where('division_id', '!=', null)->where('group_id', '!=', null)->get();
            });
            $business_groups = Cache::rememberForever('business_groups', function () {
                return BusinessGroup::select('id', 'group')->where('group', '!=', null)->get();
            });
            $business_types = Cache::rememberForever('business_types', function () {
                return BusinessType::select('id', 'type')->where('type', '!=', null)->get();
            });
            $years = Cache::remember('business_year', 2592000  , function () {
                return DB::select(DB::raw("SELECT DISTINCT year FROM businesses ORDER BY year DESC"));
            });
            
        }elseif($data["dataType"] == 'patent') {
            $categories = Cache::rememberForever('patent_classifications_with_ids', function () {
                return PatentCategory::select('id', 'classification_category')->where('class_id', null)->where('division_id', '!=', Null)->get();
            });
            $patent_kinds = Cache::rememberForever('patents_kinds', function () {
                return PatentKind::select('id', 'kind')->where('kind', '!=', null)->get();
            });
            $patent_types = Cache::rememberForever('patents_types', function () {
                return PatentType::select('id', 'type')->where('type', '!=', null)->get();
            });
            $years = Cache::remember('patent_year', 2592000  , function () {
                return DB::select(DB::raw("SELECT DISTINCT year FROM patents ORDER BY year DESC"));
            });
        }elseif($data["dataType"] == 'journal') {
            $categories = Cache::rememberForever('journal_classifications_with_ids', function () {
                return JournalCategory::select('id', 'category')->where('division_id', null)->where('section_id', '!=', Null)->get();
            });
            $years = Cache::remember('journal_year', 2592000  , function () {
                return DB::select(DB::raw("SELECT DISTINCT year FROM journals ORDER BY year DESC"));
            });
        }

        return response([
            "countries"       => $countries,
            "categories"      => $categories,
            "business_groups" => $business_groups,
            "business_types"  => $business_types,
            "patent_kinds"    => $patent_kinds,
            "patent_types"    => $patent_types,
            "years"           => $years
        ], 200);
    }
}
