<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\LeadController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/all-leads', [LeadController::class, 'getAllLeads']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('leads', [LeadController::class, 'index']);
    Route::post('leads/import', [LeadController::class, 'import']);
    Route::get('leads/export', [LeadController::class, 'export']);
});



// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/leads/import', [LeadController::class, 'import']);
//     Route::get('/leads', [LeadController::class, 'index']);
// });



// Route::get('leads', [LeadController::class, 'index'])->middleware('auth:sanctum');  
// Route::post('leads/import', [LeadController::class, 'import'])->middleware('auth:sanctum');  
// Route::get('/leads/export', [LeadController::class, 'export'])->middleware('auth:sanctum')->name('leads.export');

// Route::post('/leads', [LeadController::class, 'store']);
// Route::post('/leads/import', [LeadController::class, 'import']);