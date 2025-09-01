<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\TestOrderController;
use App\Http\Controllers\Api\TestPathologyMacroController;
use App\Http\Controllers\Api\ApiController as ApiController;

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

Route::get('/status/{code}', [ApiController::class, 'getCode']);
Route::get('', [ApiController::class, 'getCodeDB']);
Route::post('/testOrder/webhook', [ApiController::class, 'getStatus']);
Route::post('/generate/pdf', [ApiController::class, 'pdf']);



// Routes de connexion 
Route::post('/v1/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Cette route affiche toutes les affectations
    Route::get('/alltestorders', [DoctorController::class, 'AllTestOrders']);

    // route to get doctors list
    Route::get('/doctors', [DoctorController::class, 'get_doctors']);

    // Cette route permet de rechercher une affectation
    Route::get('/search/{query}', [DoctorController::class, 'searchAffectation']);

    // Cette route permet de filtrer une affectation par docteur, par son identifiant (Id)
    Route::get('/searchtestorderbydoctor/{doctorId}', [DoctorController::class, 'searchAffectationByDoctor']);

    // Cette route permet de filtrer une affectation de plus de 10 jours affecter a un docteur
    Route::get('/testorder/old/{doctorId}', [DoctorController::class, 'getOldTestOrders']);

    Route::put('/testorder/report/patient', [DoctorController::class, 'updateInformOrDeliveryPatientStatus']);

    Route::get('/testorder/{code}', [DoctorController::class, 'searchTestOrder']);

    //Macro
    Route::get('/testorders', [TestOrderController::class, 'index']);
    Route::get('/search-test-order', [TestOrderController::class, 'searchTestOrder']);
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::get('/search-macro', [TestPathologyMacroController::class, 'searchMacro']);
    Route::get('/macros', [TestPathologyMacroController::class, 'index']);
    Route::post('/macros', [TestPathologyMacroController::class, 'store']);
    Route::post('/bulk-macros', [TestPathologyMacroController::class, 'bulkStore']);
});
