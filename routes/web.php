<?php

use App\Http\Livewire\Admin\Business\BusinessAddComponent;
use App\Http\Livewire\Admin\Business\BusinessListComponent;
use App\Http\Livewire\Admin\Business\BusinessTrashedComponent;
use App\Http\Livewire\Admin\Business\BusinessUpdateComponent;
use App\Http\Livewire\Admin\Classification\ClassificationAdd;
use App\Http\Livewire\Admin\Classification\ClassificationList;
use App\Http\Livewire\Admin\DashboardComponent;
use App\Http\Livewire\Admin\Journals\JournalsAddComponent;
use App\Http\Livewire\Admin\Journals\JournalsListComponent;
use App\Http\Livewire\Admin\Journals\JournalsTrashedComponent;
use App\Http\Livewire\Admin\Journals\JournalsUpdateComponent;
use App\Http\Livewire\Admin\Patent\PatentAddComponent;
use App\Http\Livewire\Admin\Patent\PatentListComponent;
use App\Http\Livewire\Admin\Patent\PatentTrashedComponent;
use App\Http\Livewire\Admin\Patent\PatentUpdateComponent;
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
    // Business
    Route::get('business', BusinessListComponent::class)->name('business.list');
    Route::get('business/add', BusinessAddComponent::class)->name('business.add');
    Route::get('business/update', BusinessUpdateComponent::class)->name('business.update');
    Route::get('business/trashed', BusinessTrashedComponent::class)->name('business.trashed');
    Route::get('business/import/sample-download', BusinessTrashedComponent::class)->name('business.download.sample');
    // Patent
    Route::get('patent', PatentListComponent::class)->name('patent.list');
    Route::get('patent/add', PatentAddComponent::class)->name('patent.add');
    Route::get('patent/update', PatentUpdateComponent::class)->name('patent.update');
    Route::get('patent/trashed', PatentTrashedComponent::class)->name('patent.trashed');
    // Journals
    Route::get('journals', JournalsListComponent::class)->name('journals.list');
    Route::get('journals/add', JournalsAddComponent::class)->name('journals.add');
    Route::get('journals/update', JournalsUpdateComponent::class)->name('journals.update');
    Route::get('journals/trashed', JournalsTrashedComponent::class)->name('journals.trashed');
    // Classification
    Route::get('classification', ClassificationList::class)->name('classification.list');
    Route::get('classification/add', ClassificationAdd::class)->name('classification.add');
    // Route::get('classification/update', ClassificationUpdate::class)->name('classification.update');
    // Route::get('classification/trashed', ClassificationTrashed::class)->name('classification.trashed');
});

// Client
Route::name('client.')->group(function() {
    Route::get('/', HomeComponent::class)->name('home');
    // Dynamic Pages
    Route::get('/pages/{slug?}', HomeComponent::class)->name('pages');
});