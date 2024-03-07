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


// Cette route affiche toutes les affectations
Route::get('/v1/alltestorders',[DoctorController::class, 'AllTestOrders']);

// Cette route permet de rechercher une affectation
Route::get('/v1/search/{query}',[DoctorController::class, 'searchAffectation']);

// Cette route permet de filtrer une affectation par docteur, par son identifiant (Id)
Route::get('/v1/searchtestorderbydoctor/{doctorId}',[DoctorController::class, 'searchAffectationByDoctor']);

// Cette route permet de filtrer une affectation de plus de 10 jours affecter a un docteur
Route::get('/v1/testorder/old/{doctorId}',[DoctorController::class, 'getOldTestOrders']);

