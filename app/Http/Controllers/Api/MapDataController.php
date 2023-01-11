<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MapDataController extends Controller
{
    public function getMapData(Request $request)
    {
        ini_set('memory_limit', '-1');

        $validator = Validator::make($request->all(), [
            // 'searchText' => 'string',
            'dataType' => 'required|string',
            'paginationLimit' => "required|integer"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validatedData = $request->all();
        $businesses = [];
        $patents = [];
        $journals = [];


        $searchValues = explode(" ", $validatedData["searchText"]); // List of search keywords

        if ($validatedData["dataType"] == "business" || $validatedData["dataType"] == "all") {
            // $businessQuery =  DB::table('businesses')->select('id', 'lat', 'long', 'year', 'company_name', "region_id", 'group_id', 'business_type_id');
            $businessQuery =  DB::table('businesses')->select('lat as x', 'long as y');

            $tempOperation = "AND";
            if (isset($validatedData["searchText"])) {

                foreach ($searchValues as $searchValue) {
                    // $businessQuery = $businessQuery->where('company_name', 'LIKE', '%' . $searchValue . '%')->orWhere('ngc_code', 'LIKE', '%' . $searchValue . '%');
                    if ($searchValue == "AND") {
                    } else if ($searchValue == "OR") {
                        $tempOperation = "OR";
                    } else {
                        if ($tempOperation == "OR") {
                            $businessQuery = $businessQuery->orWhere('company_name', 'LIKE', '%' . $searchValue . '%');
                        } else {
                            $businessQuery = $businessQuery->where('company_name', 'LIKE', '%' . $searchValue . '%');
                        }
                        $tempOperation = "AND";
                    }
                }
            }

            if (isset($validatedData["country"]) && $validatedData["country"] != null) {
                $businessQuery = $businessQuery->where('country_id', $validatedData["country"]);
            }
            if (isset($validatedData["year"]) && $validatedData["year"] != null) {
                $businessQuery = $businessQuery->where('year', $validatedData["year"]);
            }
            if (isset($validatedData["group"]) && $validatedData["group"] != null) {
                $businessQuery = $businessQuery->where('group_id', $validatedData["group"]);
            }
            if (isset($validatedData["type"]) && $validatedData["type"] != null) {
                $businessQuery = $businessQuery->where('business_type_id', $validatedData["type"]);
            }
            if (isset($validatedData["category"]) && $validatedData["category"] != null) {
                $businessQuery = $businessQuery->where('industry_classification_id', $validatedData["category"]);
            }
            
            $businesses = $businessQuery->paginate($validatedData["paginationLimit"])->toArray();
            $businesses = array_values(array_unique($businesses));
        }

        if ($validatedData["dataType"] == "patent" || $validatedData["dataType"] == "all") {
            // $patentQuery =  DB::table('patents')->select('id', 'lat', 'long', 'year', 'title', 'kind_id', 'type_id');
            $patentQuery =  DB::table('patents')->select('lat as x', 'long as y');

            $tempOperation = "AND";
            if (isset($validatedData["searchText"])) {
                foreach ($searchValues as $searchValue) {
                    if ($searchValue == "AND") {
                    } else if ($searchValue == "OR") {
                        $tempOperation = "OR";
                    } else {
                        if ($tempOperation == "OR") {
                            $patentQuery = $patentQuery->orWhere('title', 'LIKE', '%' . $searchValue . '%');
                        } else {
                            $patentQuery = $patentQuery->where('title', 'LIKE', '%' . $searchValue . '%');
                        }
                        $tempOperation = "AND";
                    }
                }
            }

            if (isset($validatedData["country"]) && $validatedData["country"] != null) {
                $patentQuery = $patentQuery->where('country_id', $validatedData["country"]);
            }
            if (isset($validatedData["year"]) && $validatedData["year"] != null) {
                $patentQuery = $patentQuery->where('year', $validatedData["year"]);
            }
            if (isset($validatedData["kind"]) && $validatedData["kind"] != null) {
                $patentQuery = $patentQuery->where('kind_id', $validatedData["kind"]);
            }
            if (isset($validatedData["type"]) && $validatedData["type"] != null) {
                $patentQuery = $patentQuery->where('type_id', $validatedData["type"]);
            }
            if (isset($validatedData["category"]) && $validatedData["category"] != null) {
                $listOfCategories = $validatedData["category"];
                $patentQuery = $patentQuery->whereExists(function ($query) use ($listOfCategories) {
                    $query->select(DB::raw(1))
                        ->from('patent_pivot_patent_category')
                        ->where('parent_classification_id', $listOfCategories);
                });
            }
            $patents = $patentQuery->paginate($validatedData["paginationLimit"])->toArray();
            $patents = array_values(array_unique($patents));
        }

        if ($validatedData["dataType"] == "journal" || $validatedData["dataType"] == "all") {
            // $journalQuery =  DB::table('journals')->select('id', 'lat', 'long', 'title', 'year');
            $journalQuery =  DB::table('journals')->select('lat as x', 'long as y');

            $tempOperation = "AND";
            if (isset($validatedData["searchText"])) {
                foreach ($searchValues as $searchValue) {
                    if ($searchValue == "AND") {
                    } else if ($searchValue == "OR") {
                        $tempOperation = "OR";
                    } else {
                        if ($tempOperation == "OR") {
                            $journalQuery = $journalQuery->orWhere('title', 'LIKE', '%' . $searchValue . '%');
                        } else {
                            $journalQuery = $journalQuery->where('title', 'LIKE', '%' . $searchValue . '%');
                        }
                        $tempOperation = "AND";
                    }
                }
            }

            if (isset($validatedData["country"]) && $validatedData["country"] != null) {
                $journalQuery = $journalQuery->where('country_id', $validatedData["country"]);
            }
            if (isset($validatedData["year"]) && $validatedData["year"] != null) {
                $journalQuery = $journalQuery->where('year', $validatedData["year"]);
            }
            if (isset($validatedData["category"]) && $validatedData["category"] != null) {
                $listOfCategories = $validatedData["category"];
                $journalQuery = $journalQuery->whereExists(function ($query) use ($listOfCategories) {
                    $query->select(DB::raw(1))
                        ->from('journal_pivot_journal_category')
                        ->where('parent_classification_id', $listOfCategories);
                });
            }
            $journals = $journalQuery->paginate($validatedData["paginationLimit"])->toArray();
            $journals = array_values(array_unique($journals));
        }
        return response(
            ["businesses" => [
                "next_page_link" => $businesses["next_page_url"] ?? null,
                "data" => $businesses["data"] ?? null
            ], "patents" =>
            [
                "next_page_link" => $patents["next_page_url"] ?? null,
                "data" => $patents["data"] ?? null,
            ], "journals" => [
                "next_page_link" => $journals["next_page_url"] ?? null,
                "data" => $journals["data"] ?? null,
            ]]
        );
    }

}
