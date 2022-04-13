<?php

use App\Http\Controllers\TestCategoryController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
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


