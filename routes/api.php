<?php

use App\Http\Controllers\PartnerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('partners')->group(function () {
    Route::post('/', [PartnerController::class, 'create']);
    Route::get('/{id}', [PartnerController::class, 'get']);
    Route::post('/find', [PartnerController::class, 'find']);
});
