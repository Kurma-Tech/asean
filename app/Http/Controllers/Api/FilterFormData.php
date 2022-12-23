<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FilterFormData extends Controller
{
    public function getFilterFormData(Request $request) {
        $validator = Validator::make($request->all(), [
            'dataType' => 'required|string'
        ]);
        $data = $validator->validated();



        // if($data["dataType"] === 'Business') {
            
        // }
        return response(["test" => $data["dataType"]], 200);
    }
}
