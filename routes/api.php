<?php

use App\Http\Controllers\Api\ApiController as ApiController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\LoginController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/status/{code}',[ApiController::class, 'getCode']);
Route::get('',[ApiController::class, 'getCodeDB']);
Route::post('/testOrder/webhook',[ApiController::class, 'getStatus']);
Route::post('/generate/pdf',[ApiController::class, 'pdf']);



// Routes de connexion 
Route::post('/v1/login',[LoginController::class, 'login']);

// Routes de recherches
Route::get('/v1/alltestorders',[DoctorController::class, 'AllTestOrders']);
Route::post('/v1/search',[DoctorController::class, 'searchAffectation']);
Route::get('/v1/searchtestorderbydoctor/{query}',[DoctorController::class, 'searchAffectationByDoctor']);
Route::get('/v1/testorder/old/{query}',[DoctorController::class, 'getOldTestOrders']);

