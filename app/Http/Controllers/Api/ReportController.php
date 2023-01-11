<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\IndustryClassification;
use App\Models\PatentCategory;
use App\Models\JournalCategory;

class ReportController extends Controller
{
    public function getTotalChartData(Request $request)
    {
        ini_set('memory_limit', '-1');

        $validator = Validator::make($request->all(), [
            // 'searchText' => 'string',
            'dataType' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $requestedData = $request->all();
        if (isset($requestedData["searchText"])) {
            $searchValues = explode(" ", $requestedData["searchText"]); // List of search keywords
        }

        if ($requestedData["dataType"] == "business") {
            $businessQuery =  DB::table('businesses')->select('id', 'year')->where('year', '!=', '');

            $tempOperation = "AND";
            if (isset($requestedData["searchText"])) {

                foreach ($searchValues as $searchValue) {
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

            if (isset($requestedData["country"]) && $requestedData["country"] != null) {
                $businessQuery = $businessQuery->where('country_id', $requestedData["country"]);
            }
            if (isset($requestedData["year"]) && $requestedData["year"] != null) {
                $businessQuery = $businessQuery->where('year', $requestedData["year"]);
            }
            if (isset($requestedData["group"]) && $requestedData["group"] != null) {
                $businessQuery = $businessQuery->where('group_id', $requestedData["group"]);
            }
            if (isset($requestedData["type"]) && $requestedData["type"] != null) {
                $businessQuery = $businessQuery->where('business_type_id', $requestedData["type"]);
            }
            if (isset($requestedData["category"]) && $requestedData["category"] != null) {
                $businessQuery = $businessQuery->where('industry_classification_id', $requestedData["category"]);
            }

            $businesses = $businessQuery->get();
            $bussinessCountByYear = collect($businesses)->pluck('year')->countBy();
            return response(["data" => $bussinessCountByYear], 200);
        }

        if ($requestedData["dataType"] == "patent") {
            $patentQuery =  DB::table('patents')->select('id', 'year')->where('year', '!=', '');

            $tempOperation = "AND";
            if (isset($requestedData["searchText"])) {
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

            if (isset($requestedData["country"]) && $requestedData["country"] != null) {
                $patentQuery = $patentQuery->where('country_id', $requestedData["country"]);
            }
            if (isset($requestedData["year"]) && $requestedData["year"] != null) {
                $patentQuery = $patentQuery->where('year', $requestedData["year"]);
            }
            if (isset($requestedData["kind"]) && $requestedData["kind"] != null) {
                $patentQuery = $patentQuery->where('kind_id', $requestedData["kind"]);
            }
            if (isset($requestedData["type"]) && $requestedData["type"] != null) {
                $patentQuery = $patentQuery->where('type_id', $requestedData["type"]);
            }
            if (isset($requestedData["category"]) && $requestedData["category"] != null) {
                $listOfCategories = $requestedData["category"];
                $patentQuery = $patentQuery->whereExists(function ($query) use ($listOfCategories) {
                    $query->select(DB::raw(1))
                        ->from('patent_pivot_patent_category')
                        ->where('parent_classification_id', $listOfCategories);
                });
            }
            $patents = $patentQuery->get();
            $patentCountByYear =  collect($patents)->pluck('year')->countBy();
            return response(["data" => $patentCountByYear], 200);
        }

        if ($requestedData["dataType"] == "journal") {
            $journalQuery =  DB::table('journals')->select('id', 'year')->where('year', '!=', '');

            $tempOperation = "AND";
            if (isset($requestedData["searchText"])) {
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

            if (isset($requestedData["country"]) && $requestedData["country"] != null) {
                $journalQuery = $journalQuery->where('country_id', $requestedData["country"]);
            }
            if (isset($requestedData["year"]) && $requestedData["year"] != null) {
                $journalQuery = $journalQuery->where('year', $requestedData["year"]);
            }
            if (isset($requestedData["category"]) && $requestedData["category"] != null) {
                $listOfCategories = $requestedData["category"];
                $journalQuery = $journalQuery->whereExists(function ($query) use ($listOfCategories) {
                    $query->select(DB::raw(1))
                        ->from('journal_pivot_journal_category')
                        ->where('parent_classification_id', $listOfCategories);
                });
            }
            $journals = $journalQuery->get();
            $journalByYear = collect($journals)->pluck('year')->countBy();
            return response(["data" => $journalByYear], 200);
        }

        return response("Unkown Data Type", 406);
    }

    public function getPopularCategoryData(Request $request)
    {
        ini_set('memory_limit', '-1');

        $validator = Validator::make($request->all(), [
            // 'searchText' => 'string',
            'dataType' => 'required|string',
            'limit' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $requestedData = $request->all();
        if (isset($requestedData["searchText"])) {
            $searchValues = explode(" ", $requestedData["searchText"]); // List of search keywords
        }

        if ($requestedData["dataType"] == "business") {
            $businessQuery =  DB::table('businesses')->select('id', 'year', 'industry_classification_id')->where('year', '!=', '')->where('industry_classification_id', '!=', '')->where('industry_classification_id', '!=', null);

            $tempOperation = "AND";
            if (isset($requestedData["searchText"])) {

                foreach ($searchValues as $searchValue) {
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

            if (isset($requestedData["country"]) && $requestedData["country"] != null) {
                $businessQuery = $businessQuery->where('country_id', $requestedData["country"]);
            }
            if (isset($requestedData["year"]) && $requestedData["year"] != null) {
                $businessQuery = $businessQuery->where('year', $requestedData["year"]);
            }

            $businesses = $businessQuery->get();

            $emergingBusinessData = [];

            $emergingBusiness = collect($businesses)->pluck('industry_classification_id')->countBy()->sortByDesc(null)->take($requestedData["limit"]);

            foreach ($emergingBusiness as $key => $value) {
                if (IndustryClassification::find($key) != null) {
                    array_push($emergingBusinessData, [
                        "key" => IndustryClassification::find($key)->classifications,
                        "value" => $value
                    ]);
                }
            }
            return response(["data" => $emergingBusinessData], 200);
        }

        if ($requestedData["dataType"] == "patent") {
            $patentQuery =  DB::table('patent_pivot_patent_category')->select('id', 'year', 'parent_classification_id')->where('year', '!=', '')->where('parent_classification_id', '!=', '')->where('parent_classification_id', '!=', null)->distinct('patent_id', 'category_id');

            $tempOperation = "AND";
            if (isset($requestedData["searchText"])) {
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

            if (isset($requestedData["country"]) && $requestedData["country"] != null) {
                $patentQuery = $patentQuery->where('country_id', $requestedData["country"]);
            }
            if (isset($requestedData["year"]) && $requestedData["year"] != null) {
                $patentQuery = $patentQuery->where('year', $requestedData["year"]);
            }
            $patents = $patentQuery->get();
            $emergingPatentData = [];

            $emergingPatents = collect($patents)->pluck('parent_classification_id')->countBy()->sortByDesc(null)->take($requestedData["limit"]);

            foreach ($emergingPatents as $key => $value) {
                if ($key != null) {
                    array_push($emergingPatentData, [
                        "key" => PatentCategory::find($key)->classification_category,
                        "value" => $value
                    ]);
                } else {
                    continue;
                }
            }
            return response(["data" => $emergingPatentData], 200);
        }

        if ($requestedData["dataType"] == "journal") {
            $journalQuery =  DB::table('journal_pivot_journal_category')->select('id', 'year', 'parent_classification_id')->where('year', '!=', '')->where('parent_classification_id', '!=', '')->where('parent_classification_id', '!=', null)->distinct('journal_id', 'category_id');

            $tempOperation = "AND";
            if (isset($requestedData["searchText"])) {
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

            if (isset($requestedData["country"]) && $requestedData["country"] != null) {
                $journalQuery = $journalQuery->where('country_id', $requestedData["country"]);
            }
            if (isset($requestedData["year"]) && $requestedData["year"] != null) {
                $journalQuery = $journalQuery->where('year', $requestedData["year"]);
            }

            $journals = $journalQuery->get();
            $emergingJournalData = [];

            $emergingJournals = collect($journals)->pluck('parent_classification_id')->countBy()->sortByDesc(null)->take($requestedData["limit"]);

            foreach ($emergingJournals as $key => $value) {
                if ($key != null) {
                    array_push($emergingJournalData, [
                        "key" => JournalCategory::find($key)->category,
                        "value" => $value
                    ]);
                } else {
                    continue;
                }
            }
            return response(["data" => $emergingJournalData], 200);
        }

        return response("Unkown Data Type", 406);
    }
}
