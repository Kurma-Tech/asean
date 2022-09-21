<?php

use App\Http\Livewire\Admin\Business\BusinessAddComponent;
use App\Http\Livewire\Admin\Business\BusinessListComponent;
use App\Http\Livewire\Admin\Business\BusinessTrashedComponent;
use App\Http\Livewire\Admin\Business\BusinessUpdateComponent;
use App\Http\Livewire\Admin\BusinessType\BusinessListComponent as BusinessTypeBusinessListComponent;
use App\Http\Livewire\Admin\Classification\ClassificationAdd;
use App\Http\Livewire\Admin\Classification\ClassificationList;
use App\Http\Livewire\Admin\Country\CountryListComponent;
use App\Http\Livewire\Admin\DashboardComponent;
use App\Http\Livewire\Admin\Journals\JournalsAddComponent;
use App\Http\Livewire\Admin\Journals\JournalsListComponent;
use App\Http\Livewire\Admin\Journals\JournalsTrashedComponent;
use App\Http\Livewire\Admin\Journals\JournalsUpdateComponent;
use App\Http\Livewire\Admin\Manpower\ManpowerListComponent;
use App\Http\Livewire\Admin\Patent\PatentListComponent;
use App\Http\Livewire\Admin\PatentKind\PatentListComponent as PatentKindPatentListComponent;
use App\Http\Livewire\Admin\PatentType\PatentListComponent as PatentTypePatentListComponent;
use App\Http\Livewire\Client\HomeComponent;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin
Route::prefix('admin')->name('admin.')->group(function() {
    Route::get('dashboard', DashboardComponent::class)->name('dashboard');
    // Countries
    Route::get('countries', CountryListComponent::class)->name('countries.list');
    // Business
    Route::get('business', BusinessListComponent::class)->name('business.list');
    Route::get('business/add', BusinessAddComponent::class)->name('business.add');
    Route::get('business/update/{key}', BusinessUpdateComponent::class)->name('business.update');
    Route::get('business/trashed', BusinessTrashedComponent::class)->name('business.trashed');
    Route::get('business/import/sample-download', BusinessTrashedComponent::class)->name('business.download.sample');
    // BusinessType
    Route::get('business/types', BusinessTypeBusinessListComponent::class)->name('typeBusiness.list');
    // Patent
    Route::get('patent', PatentListComponent::class)->name('patent.list');
    // PatentType
    Route::get('patent/types', PatentTypePatentListComponent::class)->name('typePatent.list');
    // PatentKind
    Route::get('patent/kinds', PatentKindPatentListComponent::class)->name('kindPatent.list');
    // Journals
    Route::get('journals', JournalsListComponent::class)->name('journals.list');
    // Classification
    Route::get('classification', ClassificationList::class)->name('classification.list');
    Route::get('classification/add', ClassificationAdd::class)->name('classification.add');
    // ManPower
    Route::get('manpower', ManpowerListComponent::class)->name('manpower.list');
});

// Client
Route::name('client.')->group(function() {
    Route::get('/', HomeComponent::class)->name('home');
    // Dynamic Pages
    Route::get('/pages/{slug?}', HomeComponent::class)->name('pages');
});