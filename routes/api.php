<?php

use App\Http\Controllers\Api\FilterFormData;
use App\Http\Controllers\Api\MapDataController;
use App\Http\Controllers\Api\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'filter',
    'as' => 'filter.'
    ], 
    function () {
        Route::post('/default', [FilterFormData::class, 'getFilterFormData'])->name('default');
        Route::post('/mapdata', [MapDataController::class, 'getMapData'])->name('mapdata');
    }
);

Route::group([
    'middleware' => 'api',
    'prefix' => 'report',
    'as' => 'report.'
    ], 
    function () {
        Route::post('/totalChart', [ReportController::class, 'getTotalChartData'])->name('totalChartData');
        Route::post('/popular', [ReportController::class, 'getPopularCategoryData'])->name('popularCategoryData');
    }
);

