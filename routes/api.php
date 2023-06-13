<?php

use App\Http\Controllers\Api\ApiController as ApiController;
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

Route::get('/status/{code}',[ApiController::class, 'getCode']);
Route::get('',[ApiController::class, 'getCodeDB']);
Route::get('/testOrder/webhook',[ApiController::class, 'getStatus']);
