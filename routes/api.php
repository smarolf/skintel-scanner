<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ScanController;
use App\Http\Controllers\Api\SubmissionController;

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

// Scan API routes
Route::prefix('scan')->group(function () {
    Route::post('/capture-and-analyze', [ScanController::class, 'captureAndAnalyze']);
    Route::get('/results/{uuid}', [ScanController::class, 'getResults']);
});

// Submission API routes
Route::prefix('submissions')->group(function () {
    Route::get('/', [SubmissionController::class, 'index']);
    Route::get('/{uuid}', [SubmissionController::class, 'show']);
    Route::put('/{uuid}/personalized-routine', [SubmissionController::class, 'updatePersonalizedRoutine']);
    Route::get('/{uuid}/recommendations', [SubmissionController::class, 'getRecommendations']);
});
