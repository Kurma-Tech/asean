<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LangConroller;
use App\Http\Livewire\Admin\Business\BusinessAddComponent;
use App\Http\Livewire\Admin\Business\BusinessListComponent;
use App\Http\Livewire\Admin\Business\BusinessTrashedComponent;
use App\Http\Livewire\Admin\Business\BusinessUpdateComponent;
use App\Http\Livewire\Admin\BusinessGroup\BusinessListComponent as BusinessGroupBusinessListComponent;
use App\Http\Livewire\Admin\BusinessGroup\TrashedComponent as BusinessGroupTrashedComponent;
use App\Http\Livewire\Admin\BusinessType\BusinessListComponent as BusinessTypeBusinessListComponent;
use App\Http\Livewire\Admin\BusinessType\TrashedComponent as BusinessTypeTrashedComponent;
use App\Http\Livewire\Admin\Classification\ClassificationList;
use App\Http\Livewire\Admin\Classification\TrashedComponent as ClassificationTrashedComponent;
use App\Http\Livewire\Admin\Country\City\CityComponent;
use App\Http\Livewire\Admin\Country\City\CityTrashedComponent;
use App\Http\Livewire\Admin\Country\Region\RegionComponent;
use App\Http\Livewire\Admin\Country\Region\RegionTrashedComponent;
use App\Http\Livewire\Admin\Country\CountryListComponent;
use App\Http\Livewire\Admin\Country\CountryTrashedComponent;
use App\Http\Livewire\Admin\Country\District\DistrictComponent;
use App\Http\Livewire\Admin\Country\District\DistrictTrashedComponent;
use App\Http\Livewire\Admin\Country\Province\ProvinceComponent;
use App\Http\Livewire\Admin\Country\Province\ProvinceTrashedComponent;
use App\Http\Livewire\Admin\DashboardComponent;
use App\Http\Livewire\Admin\Journals\JournalsListComponent;
use App\Http\Livewire\Admin\Journals\JournalsTrashedComponent;
use App\Http\Livewire\Admin\JournalsCategory\CategoryListComponent;
use App\Http\Livewire\Admin\JournalsCategory\TrashedComponent as JournalsCategoryTrashedComponent;
use App\Http\Livewire\Admin\Patent\PatentListComponent;
use App\Http\Livewire\Admin\Patent\PatentTrashedComponent;
use App\Http\Livewire\Admin\PatentCategory\CategoryListComponent as PatentCategoryCategoryListComponent;
use App\Http\Livewire\Admin\PatentCategory\TrashedComponent as PatentCategoryTrashedComponent;
use App\Http\Livewire\Admin\PatentKind\PatentListComponent as PatentKindPatentListComponent;
use App\Http\Livewire\Admin\PatentKind\TrashedComponent as PatentKindTrashedComponent;
use App\Http\Livewire\Admin\PatentType\PatentListComponent as PatentTypePatentListComponent;
use App\Http\Livewire\Admin\PatentType\TrashedComponent;
use App\Http\Livewire\Admin\User\PermissionComponent;
use App\Http\Livewire\Admin\User\RoleComponent;
use App\Http\Livewire\Admin\User\UserListComponent;
use App\Http\Livewire\Client\DashboardComponent as ClientDashboardComponent;
use App\Http\Livewire\Client\HomeComponent;
use App\Http\Livewire\Client\Map\MapComponent;
use App\Http\Livewire\Client\Report\ReportComponent;
use App\Mail\UserAccount;
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
        Route::get('countries/trashed', CountryTrashedComponent::class)->name('countries.trashed.list');
        // Regions
        Route::get('countries/regions', RegionComponent::class)->name('regions.list');
        Route::get('countries/regions/trashed', RegionTrashedComponent::class)->name('regions.trashed.list');
        // Provinces
        Route::get('countries/provinces', ProvinceComponent::class)->name('provinces.list');
        Route::get('countries/provinces/trashed', ProvinceTrashedComponent::class)->name('provinces.trashed.list');
        // Districts
        Route::get('countries/districts', DistrictComponent::class)->name('districts.list');
        Route::get('countries/districts/trashed', DistrictTrashedComponent::class)->name('districts.trashed.list');
        // Cities
        Route::get('countries/cities', CityComponent::class)->name('cities.list');
        Route::get('countries/cities/trashed', CityTrashedComponent::class)->name('cities.trashed.list');
        // Business
        Route::get('business', BusinessListComponent::class)->name('business.list');
        Route::get('business/add', BusinessAddComponent::class)->name('business.add');
        Route::get('business/update/{key}', BusinessUpdateComponent::class)->name('business.update');
        Route::get('business/trashed', BusinessTrashedComponent::class)->name('business.trashed');
        Route::get('business/import/sample-download', BusinessTrashedComponent::class)->name('business.download.sample');
        // BusinessType
        Route::get('business/types', BusinessTypeBusinessListComponent::class)->name('typeBusiness.list');
        Route::get('business/types/trashed', BusinessTypeTrashedComponent::class)->name('typeBusiness.trashed.list');
        // BusinessGroup
        Route::get('business/groups', BusinessGroupBusinessListComponent::class)->name('groupBusiness.list');
        Route::get('business/groups/trashed', BusinessGroupTrashedComponent::class)->name('groupBusiness.trashed.list');
        // Patent
        Route::get('intellectual-property', PatentListComponent::class)->name('patent.list');
        Route::get('intellectual-property/trashed', PatentTrashedComponent::class)->name('patent.trashed.list');
        // PatentCategory
        Route::get('intellectual-property/category', PatentCategoryCategoryListComponent::class)->name('categoryPatent.list');
        Route::get('intellectual-property/category/trashed', PatentCategoryTrashedComponent::class)->name('categoryPatent.trashed.list');
        // PatentType
        Route::get('intellectual-property/types', PatentTypePatentListComponent::class)->name('typePatent.list');
        Route::get('intellectual-property/types/trashed', TrashedComponent::class)->name('typePatent.trashed.list');
        // PatentKind
        Route::get('intellectual-property/kinds', PatentKindPatentListComponent::class)->name('kindPatent.list');
        Route::get('intellectual-property/kinds/trashed', PatentKindTrashedComponent::class)->name('kindPatent.trashed.list');
        // Journals
        Route::get('journals', JournalsListComponent::class)->name('journals.list');
        Route::get('journals/trashed', JournalsTrashedComponent::class)->name('journals.trashed.list');
        // JournalCategory
        Route::get('journals/category', CategoryListComponent::class)->name('journalCategory.list');
        Route::get('journals/category/trashed', JournalsCategoryTrashedComponent::class)->name('journalCategory.trashed.list');
        // Classification
        Route::get('classification', ClassificationList::class)->name('classification.list');
        Route::get('classification/trashed', ClassificationTrashedComponent::class)->name('classification.trashed.list');
        // User
        Route::get('users', UserListComponent::class)->name('users.list');
        // Role
        Route::get('roles', RoleComponent::class)->name('roles.list');
        // Permission
        Route::get('permission', PermissionComponent::class)->name('permissions.list');
    });
});

// Client
Route::name('client.')->group(function() {
    Route::get('/', MapComponent::class)->name('home');
    Route::get('/report', ReportComponent::class)->name('report');
    // Dynamic Pages
    Route::get('/pages/{slug?}', HomeComponent::class)->name('pages');
    Route::get('/client/my-account', ClientDashboardComponent::class)->name('dashboard');
    Route::get('lang/change', [LangConroller::class, 'change'])->name('changeLang');
});

Route::post('logout', [AuthController::class, 'destroy'])->name('logout');
// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('email_test', function(){
    
    $context = [
        "merchant_name" => "Mid City Cafe",
        "user_name" => "Utsav Thapa",
        "branch_name" => "Maharajgunj Branch",
    ];
    return new UserAccount($context);
    // Mail::to("niweshs@gmail.com")->send(new BranchUserAssignMail($context));
});
