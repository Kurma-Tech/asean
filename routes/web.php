<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LangConroller;
use App\Http\Livewire\Admin\Business\BusinessAddComponent;
use App\Http\Livewire\Admin\Business\BusinessListComponent;
use App\Http\Livewire\Admin\Business\BusinessTrashedComponent;
use App\Http\Livewire\Admin\Business\BusinessUpdateComponent;
use App\Http\Livewire\Admin\BusinessType\BusinessListComponent as BusinessTypeBusinessListComponent;
use App\Http\Livewire\Admin\Classification\ClassificationList;
use App\Http\Livewire\Admin\Country\CountryListComponent;
use App\Http\Livewire\Admin\DashboardComponent;
use App\Http\Livewire\Admin\Journals\JournalsListComponent;
use App\Http\Livewire\Admin\JournalsCategory\CategoryListComponent;
use App\Http\Livewire\Admin\Manpower\ManpowerListComponent;
use App\Http\Livewire\Admin\Patent\PatentListComponent;
use App\Http\Livewire\Admin\PatentCategory\CategoryListComponent as PatentCategoryCategoryListComponent;
use App\Http\Livewire\Admin\PatentKind\PatentListComponent as PatentKindPatentListComponent;
use App\Http\Livewire\Admin\PatentType\PatentListComponent as PatentTypePatentListComponent;
use App\Http\Livewire\Client\DashboardComponent as ClientDashboardComponent;
use App\Http\Livewire\Client\HomeComponent;
use App\Http\Livewire\Client\Report\ReportComponent;
// use App\Http\Livewire\LoginComponent;
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
    Route::middleware(['checkAuth', 'is_admin'])->group(function() {
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
        // PatentCategory
        Route::get('patent/category', PatentCategoryCategoryListComponent::class)->name('patentCategory.list');
        // PatentType
        Route::get('patent/types', PatentTypePatentListComponent::class)->name('typePatent.list');
        // PatentKind
        Route::get('patent/kinds', PatentKindPatentListComponent::class)->name('kindPatent.list');
        // Journals
        Route::get('journals', JournalsListComponent::class)->name('journals.list');
        // JournalCategory
        Route::get('journals/category', CategoryListComponent::class)->name('journalCategory.list');
        // Classification
        Route::get('classification', ClassificationList::class)->name('classification.list');
    });
});

// Client
Route::name('client.')->group(function() {
    Route::get('/', HomeComponent::class)->name('home');
    Route::get('/report', ReportComponent::class)->name('report');
    // Dynamic Pages
    Route::get('/pages/{slug?}', HomeComponent::class)->name('pages');
    Route::get('/client/my-account', ClientDashboardComponent::class)->name('dashboard');
    Route::get('lang/change', [LangConroller::class, 'change'])->name('changeLang');
});

Route::post('logout', [AuthController::class, 'destroy'])->name('logout');
// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
