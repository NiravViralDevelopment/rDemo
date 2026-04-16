<?php

use App\Http\Controllers\CitiesController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountriesController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ManageOrderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SignaturePDFController;
use App\Http\Controllers\DummyDataController;
use App\Http\Controllers\RawDataImportController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingPaymentController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BankTransactionController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\VendorController;

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

    // Housing / Shop Booking module
    Route::get('/partners', [PartnerController::class, 'index'])->name('partners.index');
    Route::get('/partners/data', [PartnerController::class, 'data'])->name('partners.data');
    Route::get('/partners/create', [PartnerController::class, 'create'])->name('partners.create');
    Route::post('/partners', [PartnerController::class, 'store'])->name('partners.store');
    Route::get('/partners/{id}/payments/create', [PartnerController::class, 'createPayment'])->name('partners.payments.create');
    Route::post('/partners/{id}/payments', [PartnerController::class, 'storePayment'])->name('partners.payments.store');
    Route::get('/partners/{id}/edit', [PartnerController::class, 'edit'])->name('partners.edit');
    Route::put('/partners/{id}', [PartnerController::class, 'update'])->name('partners.update');
    Route::delete('/partners/{id}', [PartnerController::class, 'destroy'])->name('partners.destroy');

    Route::get('/vendors', [VendorController::class, 'index'])->name('vendors.index');
    Route::get('/vendors/data', [VendorController::class, 'data'])->name('vendors.data');
    Route::get('/vendors/create', [VendorController::class, 'create'])->name('vendors.create');
    Route::post('/vendors', [VendorController::class, 'store'])->name('vendors.store');
    Route::get('/vendors/{id}/transactions/create', [VendorController::class, 'createTransaction'])->name('vendors.transactions.create');
    Route::post('/vendors/{id}/transactions', [VendorController::class, 'storeTransaction'])->name('vendors.transactions.store');
    Route::get('/vendors/{id}/materials', [VendorController::class, 'materialsIndex'])->name('vendors.materials.index');
    Route::get('/vendors/{id}/materials/data', [VendorController::class, 'materialsData'])->name('vendors.materials.data');
    Route::post('/vendors/{id}/materials', [VendorController::class, 'materialsStore'])->name('vendors.materials.store');
    Route::get('/vendors/{vendorId}/materials/{materialId}/edit', [VendorController::class, 'materialsEdit'])->name('vendors.materials.edit');
    Route::put('/vendors/{vendorId}/materials/{materialId}', [VendorController::class, 'materialsUpdate'])->name('vendors.materials.update');
    Route::delete('/vendors/{vendorId}/materials/{materialId}', [VendorController::class, 'materialsDestroy'])->name('vendors.materials.destroy');
    Route::get('/vendors/{id}/edit', [VendorController::class, 'edit'])->name('vendors.edit');
    Route::put('/vendors/{id}', [VendorController::class, 'update'])->name('vendors.update');
    Route::delete('/vendors/{id}', [VendorController::class, 'destroy'])->name('vendors.destroy');

    Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
    Route::get('/properties/data', [PropertyController::class, 'data'])->name('properties.data');
    Route::get('/houses', [PropertyController::class, 'houses'])->name('houses.index');
    Route::get('/houses/data', [PropertyController::class, 'housesData'])->name('houses.data');
    Route::get('/shops', [PropertyController::class, 'shops'])->name('shops.index');
    Route::get('/shops/data', [PropertyController::class, 'shopsData'])->name('shops.data');
    Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::get('/properties/{id}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/properties/{id}', [PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{id}', [PropertyController::class, 'destroy'])->name('properties.destroy');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/data', [BookingController::class, 'data'])->name('bookings.data');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/bookings/{bookingId}/payments', [BookingPaymentController::class, 'index'])->name('bookings.payments.index');
    Route::get('/bookings/{bookingId}/payments/data', [BookingPaymentController::class, 'data'])->name('bookings.payments.data');
    Route::get('/bookings/{bookingId}/payments/create', [BookingPaymentController::class, 'create'])->name('bookings.payments.create');
    Route::post('/bookings/{bookingId}/payments', [BookingPaymentController::class, 'store'])->name('bookings.payments.store');
    Route::get('/bookings/{bookingId}/payments/{paymentId}/edit', [BookingPaymentController::class, 'edit'])->name('bookings.payments.edit');
    Route::put('/bookings/{bookingId}/payments/{paymentId}', [BookingPaymentController::class, 'update'])->name('bookings.payments.update');
    Route::delete('/bookings/{bookingId}/payments/{paymentId}', [BookingPaymentController::class, 'destroy'])->name('bookings.payments.destroy');
    Route::get('/banks', [BankController::class, 'index'])->name('banks.index');
    Route::get('/banks/data', [BankController::class, 'data'])->name('banks.data');
    Route::get('/banks/create', [BankController::class, 'create'])->name('banks.create');
    Route::post('/banks', [BankController::class, 'store'])->name('banks.store');
    Route::get('/banks/{id}/edit', [BankController::class, 'edit'])->name('banks.edit');
    Route::put('/banks/{id}', [BankController::class, 'update'])->name('banks.update');
    Route::delete('/banks/{id}', [BankController::class, 'destroy'])->name('banks.destroy');
    Route::get('/banks/{bankId}/transactions', [BankTransactionController::class, 'index'])->name('banks.transactions.index');
    Route::get('/banks/{bankId}/transactions/data', [BankTransactionController::class, 'data'])->name('banks.transactions.data');
    Route::get('/banks/{bankId}/transactions/create', [BankTransactionController::class, 'create'])->name('banks.transactions.create');
    Route::post('/banks/{bankId}/transactions', [BankTransactionController::class, 'store'])->name('banks.transactions.store');
    Route::get('/banks/{bankId}/transactions/{transactionId}/edit', [BankTransactionController::class, 'edit'])->name('banks.transactions.edit');
    Route::put('/banks/{bankId}/transactions/{transactionId}', [BankTransactionController::class, 'update'])->name('banks.transactions.update');
    Route::delete('/banks/{bankId}/transactions/{transactionId}', [BankTransactionController::class, 'destroy'])->name('banks.transactions.destroy');
    
   
    

});

