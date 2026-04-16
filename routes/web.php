<?php

use App\Http\Controllers\CitiesController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountriesController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ManageOrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SignaturePDFController;
use App\Http\Controllers\DummyDataController;
use App\Http\Controllers\RawDataImportController;

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
}); 
Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function() {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    
    //countries
    Route::get('countries', [CountriesController::class, 'index'])->name('countries');
    Route::get('countries-create', [CountriesController::class, 'create'])->name('countries-create');
    Route::POST('countries-store', [CountriesController::class, 'Store'])->name('countries-store');
    Route::get('countries-edit/{id}', [CountriesController::class, 'Edit'])->name('countries-edit');
    Route::POST('countries-update/{id}', [CountriesController::class, 'Update'])->name('countries-update');

    Route::get('cities', [CitiesController::class, 'index'])->name('cities');
    Route::get('cities-create', [CitiesController::class, 'create'])->name('cities-create');
    Route::POST('cities-store', [CitiesController::class, 'Store'])->name('cities-store');
    Route::get('cities-edit/{id}', [CitiesController::class, 'Edit'])->name('cities-edit');
    Route::POST('cities-update/{id}', [CitiesController::class, 'Update'])->name('cities-update');
    Route::get('/get-cities/{country}', [CitiesController::class, 'getCities']);

    Route::get('city', [CityController::class, 'index'])->name('city');
    Route::get('city-create', [CityController::class, 'create'])->name('city-create');
    Route::POST('city-store', [CityController::class, 'Store'])->name('city-store');
    Route::get('city-edit/{id}', [CityController::class, 'Edit'])->name('city-edit');
    Route::POST('city-update/{id}', [CityController::class, 'Update'])->name('city-update');
    Route::get('/get-state/{country}', [CityController::class, 'getCities']);
    // Route::get('/get-city/{country}', [CityController::class, 'getcity']);

    
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class)->except(['show']);
    Route::resource('users', UserController::class);
   
    
    
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');

    // Dummy Data (CSV import)
    Route::get('/dummy-data', [DummyDataController::class, 'index'])->name('dummy-data');
    Route::post('/dummy-data/import', [DummyDataController::class, 'import'])->name('dummy-data.import');

    // Raw Data Import (CSV import)
    Route::get('/raw-data-import', [RawDataImportController::class, 'index'])->name('raw-data-import');
    Route::get('/raw-data-import/period/view', [RawDataImportController::class, 'showPeriod'])->name('raw-data-import.period');
    Route::post('/raw-data-import/period/delete', [RawDataImportController::class, 'destroyPeriod'])->name('raw-data-import.period-delete');
    Route::post('/raw-data-import/period/process', [RawDataImportController::class, 'processPeriod'])->name('raw-data-import.period-process');
    Route::post('/raw-data-import/period/apply-rules', [RawDataImportController::class, 'applyRulesPeriod'])->name('raw-data-import.period-apply-rules');
    Route::get('/raw-data-import/import', [RawDataImportController::class, 'showImportForm'])->name('raw-data-import.import-form');
    Route::get('/raw-data-import/check-duplicate', [RawDataImportController::class, 'checkDuplicateImport'])->name('raw-data-import.check-duplicate');
    Route::post('/raw-data-import/import', [RawDataImportController::class, 'import'])->name('raw-data-import.import');
    Route::post('/raw-data-import/{id}/process', [RawDataImportController::class, 'process'])->name('raw-data-import.process');
    Route::post('/raw-data-import/{id}/apply-rules', [RawDataImportController::class, 'applyRules'])->name('raw-data-import.apply-rules');
    Route::post('/raw-data-import/{id}/apply-rules-b2c', [RawDataImportController::class, 'applyRulesB2c'])->name('raw-data-import.apply-rules-b2c');
    Route::get('/raw-data-import/{id}/download', [RawDataImportController::class, 'downloadWorkingData'])->name('raw-data-import.download');
    Route::post('/raw-data-import/{id}/delete', [RawDataImportController::class, 'destroy'])->name('raw-data-import.destroy');

});

