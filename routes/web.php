<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('/leads/create', [LeadController::class, 'create'])->name('leads.create');
    Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
   // Route::post('/leads/import', [LeadController::class, 'import'])->name('leads.import');
   // Route::get('/leads/export', [LeadController::class, 'exportJson'])->name('leads.exportJson');

   Route::get('/leads/import', [LeadController::class, 'showImportForm'])->name('leads.import.form');
    Route::post('/leads/import', [LeadController::class, 'import'])->name('leads.import');
   
});

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route::get('/all-leads/search', [LeadController::class, 'searchView'])->name('leads.search');
Route::get('/ajax/leads/search', [\App\Http\Controllers\LeadController::class, 'ajaxSearch'])->name('leads.ajaxSearch');
Route::view('/search-ajax', 'leads.search-ajax')->name('leads.search.ajax.view');



Route::get('/fix', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return 'Cleared!';
});
