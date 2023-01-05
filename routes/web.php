<?php

use App\Http\Controllers\ContratController;
use App\Http\Controllers\DetailsContratController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SettingReportTemplateController;
use App\Http\Controllers\TestCategoryController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TestOrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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
    return redirect()->route('login');
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    //CATEGORIE
    Route::get('/examens/categories', [TestCategoryController::class, 'index'])->name('examens.categories.index');
    Route::post('/examens/categories', [TestCategoryController::class, 'store'])->name('examens.categories.store');
    Route::get('/categorytest/delete/{id}', [TestCategoryController::class, 'destroy']);
    Route::get('/getcategorytest/{id}', [TestCategoryController::class, 'edit']);
    Route::post('/examens/categories/update', [TestCategoryController::class, 'update'])->name('examens.categories.update');

    //EXAMEN
    Route::get('/examens/index', [TestController::class, 'index'])->name('examens.index');
    Route::post('/examens/index', [TestController::class, 'store'])->name('examens.store');
    Route::get('/test/delete/{id}', [TestController::class, 'destroy']);
    Route::get('/gettest/{id}', [TestController::class, 'edit']);
    Route::post('/getTestAndRemise', [TestController::class, 'getTestAndRemise'])->name('examens.getTestAndRemise');
    Route::post('/examens/update', [TestController::class, 'update'])->name('examens.update');
    Route::get('/gettestremise/{contrat_id}/{category_test_id}', [DetailsContratController::class, 'getremise']);

    //PATIENTS
    Route::get('/patients/index', [PatientController::class, 'index'])->name('patients.index');
    Route::post('/patients/index', [PatientController::class, 'store'])->name('patients.store');
    Route::post('/patients/storePatient', [PatientController::class, 'storePatient'])->name('patients.storePatient');
    Route::get('/patients/delete/{id}', [PatientController::class, 'destroy']);
    Route::get('/getpatient/{id}', [PatientController::class, 'edit']);
    Route::post('/patients/update', [PatientController::class, 'update'])->name('patients.update');

    //HOSPITAL
    Route::get('/hopitals/index', [HospitalController::class, 'index'])->name('hopitals.index');
    Route::post('/hopitals/index', [HospitalController::class, 'store'])->name('hopitals.store');
    Route::post('/hopitals/storeHospital', [HospitalController::class, 'storeHospital'])->name('hopitals.storeHospital');
    Route::get('/hopitals/delete/{id}', [HospitalController::class, 'destroy']);
    Route::get('/gethopital/{id}', [HospitalController::class, 'edit']);
    Route::post('/hopitals/update', [HospitalController::class, 'update'])->name('hopitals.update');

    //DOCTOR
    Route::get('/doctors/index', [DoctorController::class, 'index'])->name('doctors.index');
    Route::post('/doctors/index', [DoctorController::class, 'store'])->name('doctors.store');
    Route::post('/doctors/storeDoctor', [DoctorController::class, 'storeDoctor'])->name('doctors.storeDoctor'); //Enregistrement docteur depuis select2
    Route::get('/doctors/delete/{id}', [DoctorController::class, 'destroy']);
    Route::get('/getdoctor/{id}', [DoctorController::class, 'edit']);
    Route::post('/doctors/update', [DoctorController::class, 'update'])->name('doctors.update');

    //CONTRATS
    Route::get('/contrats/index', [ContratController::class, 'index'])->name('contrats.index');
    Route::post('/contrats/index', [ContratController::class, 'store'])->name('contrats.store');
    Route::get('/contrats/delete/{id}', [ContratController::class, 'destroy']);
    Route::get('/contrats/close/{id}', [ContratController::class, 'close'])->name('contrats.close');
    Route::get('/getcontrat/{id}', [ContratController::class, 'edit']);
    Route::post('/contrats/update', [ContratController::class, 'update'])->name('contrats.update');

    //CONTRAT DETAILS
    Route::get('/contrats/{id}/details', [ContratController::class, 'details_index'])->name('contrat_details.index');
    Route::post('/contrats/details/store', [ContratController::class, 'details_store'])->name('contrat_details.store');
    Route::get('/contrats_details/delete/{id}', [ContratController::class, 'destroy_details']);
    Route::get('/getcontratdetails/{id}', [ContratController::class, 'contrat_details_edit']);
    Route::get('/updatecontratstatus/{id}', [ContratController::class, 'update_detail_status'])->name('contrat_details.update-status');
    Route::post('/contrats_details/update', [ContratController::class, 'contrat_details_update'])->name('contrat_details.update');

    //TEST_ORDER
    Route::get('/test_order/index', [TestOrderController::class, 'index'])->name('test_order.index');
    Route::post('/test_order/store', [TestOrderController::class, 'store'])->name('test_order.store');
    Route::get('/test_order/create', [TestOrderController::class, 'create'])->name('test_order.create');
    Route::get('/test_order/delete/{id}', [TestOrderController::class, 'destroy']);
    Route::post('/test_order/updatetest', [TestOrderController::class, 'updateTestTotal'])->name('test_order.updateorder');
    Route::get('/test_order/updatestatus/{id}', [TestOrderController::class, 'updateStatus'])->name('test_order.updatestatus');
    Route::get('/get_test_order', [TestOrderController::class, 'getTestOrders'])->name('test_order.get_test_order');
    Route::get('/get_all_test_order', [TestOrderController::class, 'getAllTestOrders'])->name('test_order.get_all_test_order');

    //details_test_order
    Route::get('/test_order/details/{id}', [TestOrderController::class, 'details_index'])->name('details_test_order.index');
    Route::post('/test_order/details', [TestOrderController::class, 'details_store'])->name('details_test_order.store');
    Route::get('/test_order/detailstest/{id}', [TestOrderController::class, 'getDetailsTest']);
    Route::post('/test_order/detailsdelete', [TestOrderController::class, 'details_destroy']);
    Route::get('/test_order/show/{id}', [TestOrderController::class, 'show'])->name('test_order.show');

    Route::prefix('report')->group(function () {
        Route::get('list', [ReportController::class, 'index'])->name('report.index');
        Route::get('show/{id}', [ReportController::class, 'show'])->name('report.show');
        Route::post('/store', [ReportController::class, 'store'])->name('report.store');
        Route::get('send_sms/{id}', [ReportController::class, 'send_sms'])->name('report.send-sms');
        Route::get('pdf/{id}', [ReportController::class, 'pdf'])->name('report.pdf');

        Route::post('report-gettemplate', [ReportController::class, 'getTemplate'])->name('template.report-getTemplate');

    });

    Route::prefix('settings')->group(function () {
        Route::get('reports', [SettingController::class, 'report_index'])->name('settings.report-index');
        Route::post('reports-store', [SettingController::class, 'report_store'])->name('report.report-store');

        // App settings
        Route::get('app', [SettingController::class, 'app'])->name('settings.app-index');
        Route::post('app-store', [SettingController::class, 'app_store'])->name('settings.app-store');

    });

    Route::get('/mm', function () {
        return view('myPDF');
    });

    Route::prefix('users')->group(function () {
        Route::get('roles', [RoleController::class, 'index'])->name('user.role-index');
        Route::get('role-create', [RoleController::class, 'create'])->name('user.role-create');
        Route::get('role-show/{slug}', [RoleController::class, 'show'])->name('user.role-show');
        Route::post('roles-store', [RoleController::class, 'store'])->name('user.role-store');
        Route::post('roles-update', [RoleController::class, 'update'])->name('user.role-update');

        // PERMISSIONS
        Route::get('permissions', [PermissionController::class, 'create'])->name('user.permission-index');
        Route::post('permissions-store', [PermissionController::class, 'store'])->name('user.permission-store');

        // Users
        Route::get('', [UserController::class, 'index'])->name('user.index');
        Route::get('user-create', [UserController::class, 'create'])->name('user.create');
        Route::post('user-store', [UserController::class, 'store'])->name('user.store');
        Route::get('role-edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::post('user-update', [UserController::class, 'update'])->name('user.update');

    });

    // Profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('profile-update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile-update-name', [ProfileController::class, 'updateName'])->name('profile.update-name');

    // Templates
    Route::prefix('templates')->group(function () {
        Route::get('reports', [SettingReportTemplateController::class, 'index'])->name('template.report-index');
        Route::get('report-create', [SettingReportTemplateController::class, 'create'])->name('template.report-create');
        Route::get('report-edit/{id}', [SettingReportTemplateController::class, 'edit'])->name('template.report-edit');
        Route::post('report-store', [SettingReportTemplateController::class, 'store'])->name('template.report-store');
        Route::get('report-delete/{id}', [SettingReportTemplateController::class, 'delete'])->name('template.report-delete');

    });

    // Factures
    Route::prefix('invoices')->group(function () {
        Route::get('', [InvoiceController::class, 'index'])->name('invoice.index');
        Route::get('create', [InvoiceController::class, 'create'])->name('invoice.create');
        Route::post('store', [InvoiceController::class, 'store'])->name('invoice.store');

    });
});
