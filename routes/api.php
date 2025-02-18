<?php

use App\Http\Controllers\BrivaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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



//tes briva
Route::prefix('snap/v1.0')->group(function () {
    Route::post('/transfer-va/inquiry', [BrivaController::class, 'inquiry']);
    Route::post('/transfer-va/payment', [BrivaController::class, 'payment']);
});
