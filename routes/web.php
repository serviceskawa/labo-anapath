<?php

use App\Http\Controllers\HospitalController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TestCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//CATEGORIE
Route::get('/examens/categories', [TestCategoryController::class , 'index'])->name('examens.categories.index');
Route::post('/examens/categories', [TestCategoryController::class , 'store'])->name('examens.categories.store');
Route::get('/categorytest/delete/{id}', [TestCategoryController::class , 'destroy']);
Route::get('/getcategorytest/{id}', [TestCategoryController::class , 'edit']);
Route::post('/examens/categories/update', [TestCategoryController::class , 'update'])->name('examens.categories.update');

//EXAMEN
Route::get('/examens/index', [TestController::class , 'index'])->name('examens.index');
Route::post('/examens/index', [TestController::class , 'store'])->name('examens.store');
Route::get('/test/delete/{id}', [TestController::class , 'destroy']);
Route::get('/gettest/{id}', [TestController::class , 'edit']);
Route::post('/examens/update', [TestController::class , 'update'])->name('examens.update');


//PATIENTS
Route::get('/patients/index', [PatientController::class , 'index'])->name('patients.index');
Route::post('/patients/index', [PatientController::class , 'store'])->name('patients.store');
Route::get('/patients/delete/{id}', [PatientController::class , 'destroy']);
Route::get('/getpatient/{id}', [PatientController::class , 'edit']);
Route::post('/patients/update', [PatientController::class , 'update'])->name('patients.update');


//HOSPITAL
Route::get('/hopitals/index', [HospitalController::class , 'index'])->name('hopitals.index');
Route::post('/hopitals/index', [HospitalController::class , 'store'])->name('hopitals.store');
Route::get('/hopitals/delete/{id}', [HospitalController::class , 'destroy']);
Route::get('/gethopital/{id}', [HospitalController::class , 'edit']);
Route::post('/hopitals/update', [HospitalController::class , 'update'])->name('hopitals.update');

