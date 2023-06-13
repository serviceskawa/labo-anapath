<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\TestOrderController;
use App\Http\Controllers\PrestationsOrderrController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PrestationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\TestCategoryController;
use App\Http\Controllers\DetailsContratController;
use App\Http\Controllers\TypeConsultationController;
use App\Http\Controllers\CategoryPrestationController;
use App\Http\Controllers\LogReportController;
use App\Http\Controllers\SettingReportTemplateController;
use App\Http\Controllers\TFAuthController;
use App\Models\AppelTestOder;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware(['tfauth'])->name('home');
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');

Route::middleware(['web'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/confirm-login', [TFAuthController::class, 'show'])->name('login.confirm');
    Route::post('/confirm-login', [TFAuthController::class, 'postAuth'])->name('login.postAuth');
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
    Route::get('/profil/{id}', [PatientController::class, 'profil'])->name('patients.profil');
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
    Route::get('/test_order/index', [TestOrderController::class, 'index2'])->name('test_order.index');
    Route::post('/test_order/store', [TestOrderController::class, 'store'])->name('test_order.store');
    Route::get('/test_order/create', [TestOrderController::class, 'create'])->name('test_order.create');
    Route::get('/test_order/delete/{id}', [TestOrderController::class, 'destroy']);
    Route::post('/test_order/updatetesttotal', [TestOrderController::class, 'updateTestTotal'])->name('test_order.updateorder');
    Route::get('/test_order/updatestatus/{id}', [TestOrderController::class, 'updateStatus'])->name('test_order.updatestatus');
    Route::get('/get_test_order', [TestOrderController::class, 'getTestOrders'])->name('test_order.get_test_order');
    Route::get('/get_all_test_order', [TestOrderController::class, 'getAllTestOrders'])->name('test_order.get_all_test_order');
    Route::get('/test_order/edit/{id}', [TestOrderController::class, 'edit'])->name('test_order.edit');
    Route::post('/test_order/update/{id}', [TestOrderController::class, 'update'])->name('test_order.update');
    Route::post('/test_order/updatetest',[TestOrderController::class, 'updateTest'])->name('test_order.updateTest');
    Route::post('/test_order/search',[TestOrderController::class, 'search'])->name('test_order.search');
    Route::get('/testOrders', [TestOrderController::class, 'getTestOrdersforDatatable'])->name('test_order.getTestOrdersforDatatable');


    Route::post('/testOrders/webhook', [AppelTestOder::class, 'store'])->name('test_order.getStatus');
    Route::get('/testOrder/webhook', [AppelTestOder::class, 'index'])->name('webhook.index');



    // Attribuer docteur signataire
    Route::get('/attribuateDoctor/{doctorId}/{orderId}', [TestOrderController::class, 'attribuateDoctor'])->name('test_order.attribuateDoctor');

    //details_test_order
    Route::get('/test_order/details/{id}', [TestOrderController::class, 'details_index'])->name('details_test_order.index');
    Route::post('/test_order/details', [TestOrderController::class, 'details_store'])->name('details_test_order.store');
    Route::get('/test_order/detailstest/{id}', [TestOrderController::class, 'getDetailsTest']);
    Route::post('/test_order/detailsdelete', [TestOrderController::class, 'details_destroy']);
    Route::post('/test_order/invoice_details/', [TestOrderController::class, 'getInvoice'])->name('test_order.invoice');
    Route::get('/test_order/show/{id}', [TestOrderController::class, 'show'])->name('test_order.show');




    //PRESTATIONS_ORDER
    Route::get('/prestations_order/index', [PrestationsOrderrController::class, 'index'])->name('prestations_order.index');
    Route::post('/prestations_order/store', [PrestationsOrderrController::class, 'store'])->name('prestations_order.store');
    Route::get('/prestations_order/create', [PrestationsOrderrController::class, 'create'])->name('prestations_order.create');
    Route::get('/prestations_order/delete/{id}', [PrestationsOrderrController::class, 'destroy']);
    // Route::post('/prestations_order/updatetest', [PrestationsOrderrController::class, 'updateTestTotal'])->name('prestations_order.updateorder');
    // Route::get('/prestations_order/updatestatus/{id}', [PrestationsOrderrController::class, 'updateStatus'])->name('prestations_order.updatestatus');
    // Route::get('/get_prestations_order', [PrestationsOrderrController::class, 'getTestOrders'])->name('prestations_order.get_prestations_order');
    // Route::get('/get_all_prestations_order', [PrestationsOrderrController::class, 'getAllTestOrders'])->name('prestations_order.get_all_prestations_order');
    Route::get('/prestations_order/edit/{id}', [PrestationsOrderrController::class, 'edit'])->name('prestations_order.edit');
    Route::post('/prestations_order/update', [PrestationsOrderrController::class, 'update'])->name('prestations_order.update');
    Route::post('/prestationsOrders', [PrestationsOrderrController::class, 'getPrestationOrder'])->name('prestations_order.getPrestationOrder');
    //Route::post('/prestation_order', [TestOrderController::class, 'getDetailsPrestation'])->name('prestations_order.getDetailsPrestation');

    Route::prefix('report')->group(function () {
        Route::get('list', [ReportController::class, 'index'])->name('report.index');
        Route::get('show/{id}', [ReportController::class, 'show'])->name('report.show');
        //Route::get('search/{q}', [ReportController::class, 'search'])->name('report.search');
        Route::post('/store', [ReportController::class, 'store'])->name('report.store');
        Route::post('/auto', [ReportController::class, 'saveauto'])->name('report.saveauto');
        Route::post('/passwordReport', [ReportController::class, 'password'])->name('report.password');
        Route::get('send_sms/{id}', [ReportController::class, 'send_sms'])->name('report.send-sms');
        Route::get('pdf/{id}', [ReportController::class, 'pdf'])->name('report.pdf');

        Route::get('/azerty', [ReportController::class, 'getReportsforDatatable'])->name('report.getReportsforDatatable');

        Route::post('report-gettemplate', [ReportController::class, 'getTemplate'])->name('template.report-getTemplate');

        // Mis à jour du statut livré
        Route::get('updateDeliver/{id}', [ReportController::class, 'updateDeliverStatus'])->name('report.updateDeliver');
    });

    Route::prefix('settings')->group(function () {
        Route::get('reports', [SettingController::class, 'report_index'])->name('settings.report-index');
        Route::post('reports-store', [SettingController::class, 'report_store'])->name('report.report-store');
        Route::get('/reports-edit/{id}', [SettingController::class, 'report_edit']);
        Route::post('reports-update', [SettingController::class, 'report_update'])->name('report.report-update');
        Route::post('reports-fonter-update', [SettingController::class, 'report_store_footer'])->name('report.footer-update');
        Route::get('reports-delete/{id}', [SettingController::class, 'report_delete']);

        // App settings
        Route::get('app', [SettingController::class, 'app'])->name('settings.app-index');
        Route::post('app-store', [SettingController::class, 'app_store'])->name('settings.app-store');
    });

    //Historique
    Route::get('log/report',[LogReportController::class, 'index'])->name('log.report-index');
    Route::get('log/show/{id}',[LogReportController::class, 'show']);
    Route::get('log/user/{id}',[LogReportController::class, 'getuser']);

    Route::get('/mm', function () {
        return view('myPDF');
    });

    Route::prefix('users')->group(function () {
        Route::get('roles', [RoleController::class, 'index'])->name('user.role-index');
        Route::get('role-create', [RoleController::class, 'create'])->name('user.role-create');
        Route::get('role-show/{slug}', [RoleController::class, 'show'])->name('user.role-show');
        Route::post('roles-store', [RoleController::class, 'store'])->name('user.role-store');
        Route::post('roles-update', [RoleController::class, 'update'])->name('user.role-update');
        Route::get('/roles-delete/{id}', [RoleController::class, 'destroy'])->name('user.role-delete');


        // PERMISSIONS
        Route::get('permissions', [PermissionController::class, 'create'])->name('user.permission-index');
        Route::post('permissions-store', [PermissionController::class, 'store'])->name('user.permission-store');

        // Users
        Route::get('', [UserController::class, 'index'])->name('user.index');
        Route::get('user-create', [UserController::class, 'create'])->name('user.create');
        Route::post('user-store', [UserController::class, 'store'])->name('user.store');
        Route::get('role-edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::post('user-update', [UserController::class, 'update'])->name('user.update');
        Route::get('role-delete/{id}', [UserController::class, 'destroy'])->name('user.delete');
        Route::get('update-status-active/{id}', [UserController::class, 'updateActiveStatus'])->name('user.statusActive');
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
        Route::get('show/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
        Route::get('store-from-order/{id}', [InvoiceController::class, 'storeFromOrder'])->name('invoice.storeFromOrder');
        Route::get('print/{id}', [InvoiceController::class, 'print'])->name('invoice.print');
        Route::get('updateStatus/{id}', [InvoiceController::class, 'updateStatus'])->name('invoice.updateStatus');
        Route::post('/invoice_confirm', [InvoiceController::class, 'confirmInvoice'])->name('invoice.confirmInvoice');
        Route::post('/invoice_cancel', [InvoiceController::class, 'cancelInvoice'])->name('invoice.cancelInvoice');
        Route::post('updatePayment', [InvoiceController::class, 'updatePayment'])->name('invoice.updatePayment');
        Route::get('/setting', [SettingController::class, 'invoice_index'])->name('invoice.setting.index');
        Route::post('/setting-Update', [SettingController::class, 'invoice_update'])->name('invoice.setting.update');
        Route::get('/business', [InvoiceController::class, 'business'])->name('invoice.business');
        Route::get('/s', [InvoiceController::class, 'getInvoiceforDatatable'])->name('invoice.getTestOrdersforDatatable');
        Route::get('/index', [InvoiceController::class, 'getInvoiceIndexForDatable'])->name('invoice.getInvoiceIndexforDatatable');
        // Route::post('/filter', [InvoiceController::class, 'filter'])->name('invoice.filter');
        // Route::get('/testchiffres', [TestOrderController::class, 'getTestOrdersforDatatable'])->name('invoice.getInvoiceforDatatable');
    });

    Route::prefix('Appointments')->group(function () {
        Route::get('', [AppointmentController::class, 'index'])->name('Appointment.index');
        Route::post('store', [AppointmentController::class, 'store'])->name('Appointment.store');
        Route::post('update', [AppointmentController::class, 'update'])->name('Appointment.update');
        Route::get('getAppointments', [AppointmentController::class, 'getAppointments'])->name('Appointment.getAppointments');
        Route::get('show/{id}', [AppointmentController::class, 'show'])->name('Appointment.show');
        Route::get('getAppointmentsById/{id}', [AppointmentController::class, 'getAppointmentsById'])->name('Appointment.getAppointmentsById');
        Route::get('delete/{id}', [AppointmentController::class, 'destroy'])->name('Appointment.delete');

        // Create consultation
        Route::get('createConsultation/{id}', [AppointmentController::class, 'createConsultation'])->name('Appointment.createConsultation');
    });


    Route::prefix('type_consultations')->group(function () {
        Route::get('', [TypeConsultationController::class, 'index'])->name('type_consultation.index');
        Route::post('store', [TypeConsultationController::class, 'store'])->name('type_consultation.store');
        Route::get('show/{id}', [TypeConsultationController::class, 'show'])->name('type_consultation.show');
        Route::get('delete/{id}', [TypeConsultationController::class, 'destroy'])->name('type_consultation.delete');
    });

    Route::prefix('consultations')->group(function () {
        Route::get('', [ConsultationController::class, 'index'])->name('consultation.index');
        Route::get('create', [ConsultationController::class, 'create'])->name('consultation.create');
        Route::post('store', [ConsultationController::class, 'store'])->name('consultation.store');
        Route::post('update/{id}', [ConsultationController::class, 'update'])->name('consultation.update');
        Route::get('show/{id}', [ConsultationController::class, 'show'])->name('consultation.show');
        Route::get('delete/{id}', [ConsultationController::class, 'destroy'])->name('consultation.delete');
        Route::get('getConsultations', [ConsultationController::class, 'getConsultations'])->name('consultation.getConsultations');

        Route::get('edit/{id}', [ConsultationController::class, 'edit'])->name('consultation.edit');
        Route::post('update_by_doctor/{id}', [ConsultationController::class, 'update_by_doctor'])->name('consultation.updateDoctor');
        Route::post('update_type_consultation', [ConsultationController::class, 'update_type_consultation'])->name('consultation.updateTypeConsultation');
    });


    Route::prefix('category_prestation')->group(function () {
        Route::get('', [CategoryPrestationController::class, 'index'])->name('categoryPrestation.index');
        Route::get('create', [CategoryPrestationController::class, 'create'])->name('categoryPrestation.create');
        Route::post('store', [CategoryPrestationController::class, 'store'])->name('categoryPrestation.store');
        Route::post('update', [CategoryPrestationController::class, 'update'])->name('categoryPrestation.update');
        Route::get('show/{id}', [CategoryPrestationController::class, 'show'])->name('categoryPrestation.show');
        Route::get('edit/{id}', [CategoryPrestationController::class, 'edit'])->name('categoryPrestation.edit');
        Route::get('delete/{id}', [CategoryPrestationController::class, 'destroy'])->name('categoryPrestation.delete');
    });
    Route::prefix('prestations')->group(function () {
        Route::get('', [PrestationController::class, 'index'])->name('prestation.index');
        Route::get('create', [PrestationController::class, 'create'])->name('prestation.create');
        Route::post('store', [PrestationController::class, 'store'])->name('prestation.store');
        Route::post('update', [PrestationController::class, 'update'])->name('prestation.update');
        Route::get('show/{id}', [PrestationController::class, 'show'])->name('prestation.show');
        Route::get('edit/{id}', [PrestationController::class, 'edit'])->name('prestation.edit');
        Route::get('delete/{id}', [PrestationController::class, 'destroy'])->name('prestation.delete');

        Route::get('show_by_id/{id}', [PrestationController::class, 'show_by_id'])->name('prestation.showById');
    });
});
