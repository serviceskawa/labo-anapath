<?php

use App\Http\Controllers\AppelTestOderController;
use App\Http\Controllers\ExpenseCategorieController;
use App\Http\Controllers\CashboxDailyController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\EmployeeContratController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\EmployeeTimeoffController;
use App\Http\Controllers\EmployeePayrollController;
use App\Http\Controllers\EmployeeController;
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
use App\Http\Controllers\DocumentationCategorieController;
use App\Http\Controllers\PrestationController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AssignmentDoctorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CashboxController;
use App\Http\Controllers\CashboxTicketController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\TestCategoryController;
use App\Http\Controllers\DetailsContratController;
use App\Http\Controllers\TypeConsultationController;
use App\Http\Controllers\CategoryPrestationController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DocController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogReportController;
use App\Http\Controllers\ProblemCategoryController;
use App\Http\Controllers\ProblemeReportersController;
use App\Http\Controllers\RefundRequestController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingAppController;
use App\Http\Controllers\SettingReportTemplateController;
use App\Http\Controllers\SignalController;
use App\Http\Controllers\SupplierCategorieController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TestOrderAssignmentController;
use App\Http\Controllers\TestPathologyMacroController;
use App\Http\Controllers\TFAuthController;
use App\Http\Controllers\UnitMeasurementController;
use App\Models\AppelTestOder;
use App\Models\Article;
use App\Models\CashboxDaily;
use App\Models\Document;
use App\Models\DocumentationCategorie;
use App\Models\EmployeeTimeoff;
use App\Models\ExpenseCategorie;
use App\Models\Movement;
use App\Models\ProblemCategory;
use App\Models\UnitMeasurement;
use Laravel\SerializableClosure\Serializers\Signed;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(['tfauth']);
// Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');

Route::middleware(['web'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home/invoiceByDay', [App\Http\Controllers\HomeController::class, 'invoiceByDay'])->name('home.invoiceByDay');
    Route::get('/home/testOrderByStatus', [App\Http\Controllers\HomeController::class, 'testorderStatus'])->name('home.testorderStatus');

    Route::get('/confirm-login', [TFAuthController::class, 'show'])->name('login.confirm');
    Route::post('/confirm-login', [TFAuthController::class, 'postAuth'])->name('login.postAuth');
    //CATEGORIE
    Route::get('/examens/categories', [TestCategoryController::class, 'index'])->name('examens.categories.index');
    Route::post('/examens/categories', [TestCategoryController::class, 'store'])->name('examens.categories.store');
    Route::get('/categorytest/delete/{id}', [TestCategoryController::class, 'destroy']);
    Route::get('/getcategorytest/{id}', [TestCategoryController::class, 'edit']);
    Route::post('/examens/categories/update', [TestCategoryController::class, 'update'])->name('examens.categories.update');
    //Fournisseur

    Route::prefix('fournisseur')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('supplier.index');
        Route::post('/', [SupplierController::class, 'store'])->name('supplier.store');
        Route::get('/delete/{id}', [SupplierController::class, 'destroy']);
        Route::get('/get/{id}', [SupplierController::class, 'edit']);
        Route::post('/update', [SupplierController::class, 'update'])->name('supplier.update');
        Route::get('/getSupplier', [SupplierController::class, 'getSupplier']);
        Route::get('/categories', [SupplierCategorieController::class, 'index'])->name('supplier.categories.index');
        Route::post('/categories', [SupplierCategorieController::class, 'store'])->name('supplier.categories.store');
        Route::get('/category/delete/{id}', [SupplierCategorieController::class, 'destroy']);
        Route::get('/getcategory/{id}', [SupplierCategorieController::class, 'edit']);
        Route::post('/categories/update', [SupplierCategorieController::class, 'update'])->name('supplier.categories.update');
    });

    //EXAMEN
    Route::get('/examens/index', [TestController::class, 'index'])->name('examens.index');
    Route::post('/examens/index', [TestController::class, 'store'])->name('examens.store');
    Route::get('/test/delete/{id}', [TestController::class, 'destroy']);
    Route::get('/gettest/{id}', [TestController::class, 'edit']);
    Route::post('/getTestAndRemise', [TestController::class, 'getTestAndRemise'])->name('examens.getTestAndRemise');
    Route::post('/examens/update', [TestController::class, 'update'])->name('examens.update');
    Route::post('/get-exam-price/{id}', [TestController::class, 'getExamPrice'])->name('examens.getExamPrice');
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


    //CLIENTS
    Route::get('/clients/index', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/clients/index', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/delete/{id}', [ClientController::class, 'destroy']);
    Route::get('/getclient/{id}', [ClientController::class, 'edit']);
    Route::post('/clients/update', [ClientController::class, 'update'])->name('clients.update');
    // Route::post('/clients/storeDoctor', [ClientController::class, 'storeDoctor'])->name('clients.storeDoctor'); //Enregistrement docteur depuis select2

    //CONTRATS
    Route::get('/contrats/index', [ContratController::class, 'index'])->name('contrats.index');
    Route::post('/contrats/index', [ContratController::class, 'store'])->name('contrats.store');
    Route::get('/contrats/delete/{id}', [ContratController::class, 'destroy']);
    Route::get('/contrats/close/{id}', [ContratController::class, 'close'])->name('contrats.close');
    Route::get('/getcontrat/{id}', [ContratController::class, 'edit']);
    Route::post('/contrats/update', [ContratController::class, 'update'])->name('contrats.update');
    Route::get('/contrats/create/{contrat}/examen', [ContratController::class, 'create_examen_reduction'])->name('contrats.create_examen_reduction');
    Route::get('/contrats/edit/{detail_contrat}/examen', [ContratController::class, 'edit_examen_reduction'])->name('contrats.edit_examen_reduction');
    Route::put('/contrats/update/{detail_contrat}/examen', [ContratController::class, 'update_examen_reduction'])->name('contrats.update_examen_reduction');

    //CONTRAT DETAILS
    Route::get('/contrats/{id}/details', [ContratController::class, 'details_index'])->name('contrat_details.index');
    Route::post('/contrats/details/store', [ContratController::class, 'details_store'])->name('contrat_details.store');
    Route::post('/contrats/details/store/test', [ContratController::class, 'details_store_test'])->name('contrat_details.store_test');
    Route::get('/contrats_details/delete/{id}', [ContratController::class, 'destroy_details']);
    Route::get('/getcontratdetails/{id}', [ContratController::class, 'contrat_details_edit']);
    Route::post('/contrats_details/update', [ContratController::class, 'contrat_details_update'])->name('contrat_details.update');
    Route::get('/contrats', [ContratController::class, 'getContratsforDatatable'])->name('contrat.getContratsforDatatable');
    Route::get('/getTestsforDatatable/test', [TestController::class, 'getTestsforDatatable'])->name('test.getTestsforDatatable');
    Route::get('/updatecontratstatus/{id}', [ContratController::class, 'update_detail_status'])->name('contrat_details.update-status');



    //TEST_ORDER
    Route::get('/test_order/myspace/{idDoctor}', [TestOrderController::class, 'statistique'])->name('myspace.index');
    Route::get('/test_order/index', [TestOrderController::class, 'index2'])->name('test_order.index');
    Route::get('/test_order/index-immuno', [TestOrderController::class, 'index_immuno'])->name('test_order.immuno.index');
    Route::get('/test_order/envents', [TestOrderController::class, 'getEvent']);
    Route::post('/test_order/store', [TestOrderController::class, 'store'])->name('test_order.store');
    Route::get('/test_order/create', [TestOrderController::class, 'create'])->name('test_order.create');
    Route::get('/test_order/delete/{id}', [TestOrderController::class, 'destroy']);
    Route::post('/test_order/updatetesttotal', [TestOrderController::class, 'updateTestTotal'])->name('test_order.updateorder');
    Route::get('/test_order/updatestatus/{id}', [TestOrderController::class, 'updateStatus'])->name('test_order.updatestatus');
    Route::get('/get_test_order', [TestOrderController::class, 'getTestOrders'])->name('test_order.get_test_order');
    Route::get('/get_all_test_order', [TestOrderController::class, 'getAllTestOrders'])->name('test_order.get_all_test_order');
    Route::get('/test_order/edit/{id}', [TestOrderController::class, 'edit'])->name('test_order.edit');
    Route::post('/test_order/update/{id}', [TestOrderController::class, 'update'])->name('test_order.update');
    Route::post('/test_order/updatetest', [TestOrderController::class, 'updateTest'])->name('test_order.updateTest');
    Route::post('/test_order/search', [TestOrderController::class, 'search'])->name('test_order.search');
    Route::get('/testOrders', [TestOrderController::class, 'getTestOrdersforDatatable'])->name('test_order.getTestOrdersforDatatable');
    Route::get('/testOrders2', [TestOrderController::class, 'getTestOrdersforDatatable2'])->name('test_order.getTestOrdersforDatatable2');
    Route::get('/testOrders-immuno', [TestOrderController::class, 'getTestOrdersforDatatable_immuno'])->name('test_order.getTestOrdersforDatatable3');
    Route::get('/testOrders-immuno2', [TestOrderController::class, 'getTestOrdersforDatatable_immuno2'])->name('test_order.getTestOrdersforDatatable4');
    // Cette la route associer aux fichiers à supprimer
    Route::delete('/testOrders/delete/image-gallerie/{index}/{test_order}', [TestOrderController::class, 'deleteimagegallerie'])->name('test_order.deleteimagegallerie');
    Route::put('/testOrders/create/image-gallerie/{test_order}', [TestOrderController::class, 'createimagegallerie'])->name('test_order.createimagegallerie');


    Route::get('/testOrder/myspace', [TestOrderController::class, 'getTestOrdersforDatatableMySpace'])->name('test_order.getTestOrdersforDatatableMySpace');
    Route::get('/testOrder/myspace2', [TestOrderController::class, 'getTestOrdersforDatatableMySpace2'])->name('test_order.getTestOrdersforDatatableMySpace2');


    Route::post('/images/upload', [TestOrderController::class, 'upload'])->name('images.upload');
    Route::get('/examen-images/{examenCode}', [TestOrderController::class, 'getExamImages'])->name('images.getExamImages');

    Route::post('/testOrders/webhook', [AppelTestOderController::class, 'store'])->name('test_order.getStatus');
    Route::get('/testOrder/webhook', [AppelTestOderController::class, 'index'])->name('webhook.index');

    Route::get('/signal', [SignalController::class, 'index'])->name('signals.index');
    Route::post('/store-signal', [SignalController::class, 'store'])->name('signal.store');

    // Cette la route associée aux fichiers pour la suppression des images
    Route::delete('/testOrders/delete/image-gallerie/{index}/{test_order}', [TestOrderController::class, 'deleteimagegallerie'])->name('test_order.deleteimagegallerie');
    // Cette la route associée aux fichiers creation
    Route::put('/testOrders/create/image-gallerie/{test_order}', [TestOrderController::class, 'createimagegallerie'])->name('test_order.createimagegallerie');


    //Macro
    Route::get('/macro/index', [TestPathologyMacroController::class, 'index'])->name('macro.index');
    Route::get('/macro/index-immuno', [TestPathologyMacroController::class, 'index_immuno'])->name('macro.immuno.index');
    Route::get('/macro/dataTable', [TestPathologyMacroController::class, 'getTestOrdersforDatatable'])->name('macro.getTestOrdersforDatatable');
    Route::get('/macro/dataTable2', [TestPathologyMacroController::class, 'getTestOrdersforDatatable2'])->name('macro.getTestOrdersforDatatable2');
    Route::get('/macro/dataTable3', [TestPathologyMacroController::class, 'getTestOrdersforDatatable3'])->name('macro.getTestOrdersforDatatable3');
    Route::get('/macro/dataTableHistologie', [TestPathologyMacroController::class, 'getTestOrdersforDatatableHistologie'])->name('macro.getTestOrdersforDatatableHistologie');
    Route::get('/macro/dataTablePieceOperatoire', [TestPathologyMacroController::class, 'getTestOrdersforDatatablePieceOperatoire'])->name('macro.getTestOrdersforDatatablePieceOperatoire');
    Route::get('/macro/dataTableCytologie', [TestPathologyMacroController::class, 'getTestOrdersforDatatableCytologie'])->name('macro.getTestOrdersforDatatableCytologie');

    Route::get('/macro/dataTable-immuno', [TestPathologyMacroController::class, 'getTestOrdersforDatatable_immuno'])->name('macro.immuno.getTestOrdersforDatatable');
    Route::get('/macro/dataTable2-immuno', [TestPathologyMacroController::class, 'getTestOrdersforDatatable2_immuno'])->name('macro.immuno.getTestOrdersforDatatable2');
    Route::get('/macro/dataTable3-immuno', [TestPathologyMacroController::class, 'getTestOrdersforDatatable3_immuno'])->name('macro.immuno.getTestOrdersforDatatable3');

    Route::get('/macro/create', [TestPathologyMacroController::class, 'create'])->name('macro.create');
    Route::get('/macro/create-immuno', [TestPathologyMacroController::class, 'create_immuno'])->name('macro.immuno.create');
    Route::get('/macro/countData', [TestPathologyMacroController::class, 'countData'])->name('macro.countData');
    Route::post('/macro/create', [TestPathologyMacroController::class, 'store'])->name('macro.store');
    Route::post('/macro/one-create', [TestPathologyMacroController::class, 'store2'])->name('macro.store2');
    Route::post('/macro/update', [TestPathologyMacroController::class, 'update'])->name('macro.update');
    Route::get('/macro/delete/{id}', [TestPathologyMacroController::class, 'destroy'])->name('macro.delete');

    //Rapporte d'erreur

    //Catégories d'erreur
    Route::get('/categorie-erreur', [ProblemCategoryController::class, 'index'])->name('categorie.erreur.index');
    Route::post('/categorie-erreur', [ProblemCategoryController::class, 'store'])->name('categorie.erreur.store');
    Route::get('/getcategorie-erreur/{id}', [ProblemCategoryController::class, 'edit'])->name('categorie.erreur.edit');
    Route::post('/categorie-erreur/update', [ProblemCategoryController::class, 'update'])->name('categorie.erreur.update');
    Route::get('/categorie-erreur/delete/{id}', [ProblemCategoryController::class, 'destroy'])->name('categorie.erreur.destroy');

    //Problèmes signalés
    Route::get('/problemereport', [ProblemeReportersController::class, 'index'])->name('probleme.report.index');
    Route::get('/problemereport/create', [ProblemeReportersController::class, 'create'])->name('probleme.report.create');
    Route::post('/problemereport', [ProblemeReportersController::class, 'store'])->name('probleme.report.store');
    Route::get('/getproblemereport/{id}', [ProblemeReportersController::class, 'edit'])->name('probleme.report.edit');
    Route::post('/problemereport/update', [ProblemeReportersController::class, 'update'])->name('probleme.report.update');
    Route::post('/problemereport/send-chat-bot', [ProblemeReportersController::class, 'sendMessage'])->name('probleme.report.sendMessage');
    Route::get('/problemereport/delete/{id}', [ProblemeReportersController::class, 'destroy'])->name('probleme.report.destroy');

    //Demande de remboursement
    Route::get('/refund-request', [RefundRequestController::class, 'index'])->name('refund.request.index');
    Route::get('/refund-request/create', [RefundRequestController::class, 'create'])->name('refund.request.create');
    Route::post('/refund-request', [RefundRequestController::class, 'store'])->name('refund.request.store');
    Route::get('/getrefund-request/{id}', [RefundRequestController::class, 'edit'])->name('refund.request.edit');
    Route::post('/refund-request/update', [RefundRequestController::class, 'update'])->name('refund.request.update');
    Route::post('/refund-request/updateStatus', [RefundRequestController::class, 'updateStatus'])->name('refund.request.updateStatus');
    Route::get('/refund-request/delete/{id}', [RefundRequestController::class, 'destroy'])->name('refund.request.destroy');
    //Categorie
    Route::get('/refund-request-categorie', [RefundRequestController::class, 'index_categorie'])->name('refund.request.categorie.index');
    Route::post('/refund-request-categorie', [RefundRequestController::class, 'store_categorie'])->name('refund.request.categorie.store');
    Route::get('/refund-request-categorie/{id}', [RefundRequestController::class, 'edit_categorie']);
    Route::post('/refund-request-categorie/update', [RefundRequestController::class, 'update_categorie'])->name('refund.request.categorie.update');
    Route::get('/refund-request-categorie/delete/{id}', [RefundRequestController::class, 'destroy_categorie'])->name('refund.request.categorie.destroy');

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

        //Affectation de compte rendu
        Route::get('assignment/index', [TestOrderAssignmentController::class, 'index'])->name('report.assignment.index');
        Route::get('assignment/index-immuno', [TestOrderAssignmentController::class, 'index_immuno'])->name('report.assignment.immuno.index');
        Route::get('assignment/create/{id}', [AssignmentDoctorController::class, 'create'])->name('report.assignment.create');
        Route::get('assignment/create-immuno/{id}', [AssignmentDoctorController::class, 'create_immuno'])->name('report.assignment.immuno.create');
        Route::post('assignment/index', [TestOrderAssignmentController::class, 'store'])->name('report.assignment.store');
        Route::post('assignment/update', [TestOrderAssignmentController::class, 'update'])->name('report.assignment.update');
        Route::get('assignment/detail/{id}', [TestOrderAssignmentController::class, 'index_detail'])->name('report.assignment.detail.index');
        Route::get('assignment/detail-immuno/{id}', [TestOrderAssignmentController::class, 'index_immuno_detail'])->name('report.assignment.immuno.detail.index');
        Route::post('assignment/detail/assignment', [TestOrderAssignmentController::class, 'store_detail'])->name('report.assignment.detail.store');
        Route::get('assignment/detail/assignment/{id}', [TestOrderAssignmentController::class, 'getdetail'])->name('report.assignment.getDetail');
        Route::get('assignment/detail/destroy/{id}', [TestOrderAssignmentController::class, 'detail_destroy'])->name('report.assignment.detail.destroy');
        Route::get('assignment/print/{id}', [TestOrderAssignmentController::class, 'print'])->name('report.assignment.print');
        Route::get('/assignmen/dataTable', [TestOrderAssignmentController::class, 'getTestOrdersforDatatable'])->name('assignment.getTestOrdersforDatatable');
        // Route::get('assignment/pdf/{id}', [AssignmentDoctorController::class, 'pdf'])->name('report.assignment.pdf');

        Route::get('/azerty', [ReportController::class, 'getReportsforDatatable'])->name('report.getReportsforDatatable');
        Route::get('/report/rapport', [ReportController::class, 'getReportsRapportsforDatatable'])->name('report.getReportsRapportsforDatatable');


        Route::post('report-gettemplate', [ReportController::class, 'getTemplate'])->name('template.report-getTemplate');

        // Mis à jour du statut livré
        Route::get('updateDeliver/{id}', [ReportController::class, 'updateDeliverStatus'])->name('report.updateDeliver');
        Route::get('callOrSendSms/{id}', [ReportController::class, 'callOrSendSms'])->name('report.callOrSendSms');

        Route::get('/dataTable/suivi', [ReportController::class, 'getTestOrdersforDatatableSuivi'])->name('report.getTestOrdersforDatatableSuivi');
        Route::get('/suivi/patient-livrer/{report}', [ReportController::class, 'deliveredPatient'])->name('report.delivered.patient');
        Route::post('/suivi/store/signature', [ReportController::class, 'storeSignature'])->name('suivi.report.signature.store');
        Route::post('/reports/storeTags', [ReportController::class, 'storeTags'])->name('report.storeTags'); //Enregistrement tags depuis select2

        Route::get('/suivi/index', [ReportController::class, 'indexsuivi'])->name('report.index.suivi');
        Route::get('/report/suivi/index', [ReportController::class, 'indexsuivistatistique'])->name('report.statistique.index.suivi');
        Route::post('/suivi/store/informe', [ReportController::class, 'UpdateInformePatient'])->name('report.UpdateInformePatient');
        Route::post('/suivi/store/livrer', [ReportController::class, 'UpdateLivrePatient'])->name('report.UpdateLivrePatient');
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
        Route::get('app-new', [SettingAppController::class, 'index'])->name('settings.index');
        Route::post('app-store-new', [SettingAppController::class, 'store'])->name('settings.store');
    });

    //Historique
    Route::get('log/report', [LogReportController::class, 'index'])->name('log.report-index');
    Route::get('log/show/{id}', [LogReportController::class, 'show']);
    Route::get('log/user/{id}', [LogReportController::class, 'getuser']);

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
        Route::get('check-role/{id}', [UserController::class, 'checkrole'])->name('user.check.role');
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
        Route::post('/search-invoice', [InvoiceController::class, 'searchInvoice'])->name('invoice.business.search');
        Route::get('/s', [InvoiceController::class, 'getInvoiceforDatatable'])->name('invoice.getTestOrdersforDatatable');
        Route::get('/index', [InvoiceController::class, 'getInvoiceIndexForDatable'])->name('invoice.getInvoiceIndexforDatatable');
        Route::get('/checkCode', [InvoiceController::class, 'checkCode']);
        Route::get('/getInvoice/{id}', [InvoiceController::class, 'getInvoice']);

        Route::post('/payment/store', [PaymentController::class, 'store'])->name('payment.store');
        Route::get('/payment/store/storejs', [PaymentController::class, 'storejs'])->name('payment.storejs');
        Route::get('/payment/check/payement', [PaymentController::class, 'checkPaymentStatus'])->name('payment.checkPaymentStatus');

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

    //Banque et caisse
    Route::prefix('bank')->group(function () {
        Route::get('/', [BankController::class, 'index'])->name('bank.index');
        Route::post('/', [BankController::class, 'store'])->name('bank.store');
        Route::get('/getBank/{id}', [BankController::class, 'edit']);
        Route::post('/update', [BankController::class, 'update'])->name('bank.update');
        Route::get('/delete/{id}', [BankController::class, 'destroy']);
    });

    Route::prefix('cashbox')->group(function () {
        Route::get('/vente', [CashboxController::class, 'index'])->name('cashbox.vente.index');
        Route::post('/store-bank', [CashboxController::class, 'store_bank'])->name('cashbox.vente.store.bank');
        Route::get('/depense', [CashboxController::class, 'index_depense'])->name('cashbox.depense.index');
        Route::get('/getcashvente/{id}', [CashboxController::class, 'edit']);
        Route::post('/vente-update', [CashboxController::class, 'update'])->name('cashbox.vente.update');
        Route::get('/vente-delete/{id}', [CashboxController::class, 'destroy']);
        Route::post('/depense', [CashboxController::class, 'store'])->name('cashbox.depense.store');
        Route::get('tickets', [CashboxTicketController::class, 'index'])->name('cashbox.ticket.index');
        Route::post('tickets', [CashboxTicketController::class, 'store'])->name('cashbox.ticket.store');
        Route::post('tickets-update', [CashboxTicketController::class, 'update'])->name('cashbox.ticket.update');
        Route::get('/ticket-detail/{id}', [CashboxTicketController::class, 'detail_index'])->name('cashbox.ticket.details.index');
        Route::post('ticket-detail', [CashboxTicketController::class, 'detail_store'])->name('cashbox.ticket_detail.store');
        Route::get('/ticket-delete/{id}', [CashboxTicketController::class, 'destroy']);
        Route::get('/ticket-detail-delete/{id}', [CashboxTicketController::class, 'detail_destroy']);
        Route::post('/ticket-update-status', [CashboxTicketController::class, 'updateStatus'])->name('cashbox.ticket.updateStatus');
        Route::post('/ticket-status-update', [CashboxTicketController::class, 'updateTicketStatus'])->name('cashbox.ticket.status.update');
        Route::post('/ticket-update-total', [CashboxTicketController::class, 'updateTotal'])->name('cashbox.ticket.updateTotal');

        Route::get('ticket/getdetail/{id}', [CashboxTicketController::class, 'getTicketDetail'])->name('cashbox.ticket.getTicketDetail');
    });


    // Articles
    // Route::prefix('articles')->group(function () {
    Route::get('articles', [ArticleController::class, 'index'])->name('article.index');
    Route::get('article-create', [ArticleController::class, 'create'])->name('article.create');
    Route::get('article-edit/{article}', [ArticleController::class, 'edit'])->name('article.edit');
    Route::put('article-update/{article}', [ArticleController::class, 'update'])->name('article.update');
    Route::post('article-store', [ArticleController::class, 'store'])->name('article.store');
    Route::get('article-delete/{article}', [ArticleController::class, 'delete'])->name('article.delete');
    Route::get('/getArticle', [ArticleController::class, 'getArticle']);
    // });


    // Movements
    // Route::prefix('movements')->group(function () {
    Route::get('movements', [MovementController::class, 'index'])->name('movement.index');
    Route::get('movement-create', [MovementController::class, 'create'])->name('movement.create');
    Route::get('movement-edit/{mouvement}', [MovementController::class, 'edit'])->name('movement.edit');
    Route::put('movement-update/{mouvement}', [MovementController::class, 'update'])->name('movement.update');
    Route::post('movement-store', [MovementController::class, 'store'])->name('movement.store');
    Route::get('movement-delete/{mouvement}', [MovementController::class, 'delete'])->name('movement.delete');
    // });


    // UnitMeasurement
    // Route::prefix('unit_measurements')->group(function () {
    Route::get('unit_measurements', [UnitMeasurementController::class, 'index'])->name('unit.index');
    Route::get('unit_measurement-create', [UnitMeasurementController::class, 'create'])->name('unit.create');
    Route::get('unit_measurement-edit/{unitMeasurement}', [UnitMeasurementController::class, 'edit'])->name('unit.edit');
    Route::put('unit_measurement-update/{unitMeasurement}', [UnitMeasurementController::class, 'update'])->name('unit.update');
    Route::post('unit_measurement-store', [UnitMeasurementController::class, 'store'])->name('unit.store');
    Route::get('unit_measurement-delete/{unitMeasurement}', [UnitMeasurementController::class, 'delete'])->name('unit.delete');
    // });


    // Expense Categorie
    // Route::prefix('expense_categories')->group(function () {
    Route::get('expense_categories', [ExpenseCategorieController::class, 'index'])->name('expense.index');
    Route::get('expense_categorie-create', [ExpenseCategorieController::class, 'create'])->name('expense.create');
    Route::get('expense_categorie-edit/{expenseCategorie}', [ExpenseCategorieController::class, 'edit'])->name('expense.edit');
    Route::put('expense_categorie-update/{expenseCategorie}', [ExpenseCategorieController::class, 'update'])->name('expense.update');
    Route::post('expense_categorie-store', [ExpenseCategorieController::class, 'store'])->name('expense.store');
    Route::get('expense_categorie-delete/{expenseCategorie}', [ExpenseCategorieController::class, 'delete'])->name('expense.delete');
    // });


    // Expense Expense
    // Route::prefix('expenses')->group(function () {
    Route::get('expense', [ExpenseController::class, 'index'])->name('all_expense.index');
    Route::get('/expense-detail/{id}', [ExpenseController::class, 'detail_index'])->name('expense.details.index');
    Route::get('expense-create', [ExpenseController::class, 'create'])->name('all_expense.create');
    Route::get('expense-edit/{expense}', [ExpenseController::class, 'edit'])->name('all_expense.edit');
    Route::post('expense-update', [ExpenseController::class, 'update'])->name('all_expense.update');
    Route::get('expense/getExpenseDetail/{id}', [ExpenseController::class, 'getExpenceDetail'])->name('expense.getDetail');
    Route::post('/expense-update-total', [ExpenseController::class, 'updateTotal'])->name('expense.updateTotal');
    Route::post('expense-store', [ExpenseController::class, 'store'])->name('all_expense.store');
    Route::post('expense-detail', [ExpenseController::class, 'detail_store'])->name('expense.detail.store');
    Route::get('expense-delete/{expense}', [ExpenseController::class, 'delete'])->name('all_expense.delete');
    Route::get('/expense-detail-delete/{id}', [ExpenseController::class, 'detail_destroy']);
    Route::get('/expense-status/{id}', [ExpenseController::class, 'expense_paid'])->name('expense.paid');
    Route::get('/expense-detail-mouv-stock/{id}', [ExpenseController::class, 'update_stock_mouv'])->name('update.stock.mouv');
    // });



    // Expense CashboxDaily
    // Route::prefix('cashbox_dailies')->group(function () {
    Route::get('cashbox-daily', [CashboxDailyController::class, 'index'])->name('daily.index');
    Route::get('cashbox-daily-create', [CashboxDailyController::class, 'create'])->name('daily.create');
    Route::get('cashbox-daily-edit/{cashboxDaily}', [CashboxDailyController::class, 'edit'])->name('daily.edit');
    Route::put('cashbox-daily-update', [CashboxDailyController::class, 'update'])->name('daily.update');
    Route::post('cashbox-daily-store', [CashboxDailyController::class, 'store'])->name('daily.store');
    Route::get('cashbox-daily-delete/{cashboxDaily}', [CashboxDailyController::class, 'delete'])->name('daily.delete');
    Route::get('cashbox-daily-print/{id}', [CashboxDailyController::class, 'print'])->name('daily.print');
    Route::get('cashbox-daily-fermeture/{cashboxDaily}', [CashboxDailyController::class, 'detail_fermeture_caisse'])->name('daily.fermeture');


    // Employees
    // Route::prefix('employees')->group(function () {
    Route::get('employees', [EmployeeController::class, 'index'])->name('employee.index');
    Route::get('employee/details/{employee}', [EmployeeController::class, 'details'])->name('employee.detail');
    Route::get('employee-create', [EmployeeController::class, 'create'])->name('employee.create');
    Route::get('employee-edit/{employee}', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::get('laborantin/{id}', [EmployeeController::class, 'edit2'])->name('employee.edit2');
    Route::put('employee-update/{employee}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::post('employee-store', [EmployeeController::class, 'store'])->name('employee.store');
    Route::get('employee-delete/{employee}', [EmployeeController::class, 'delete'])->name('employee.delete');
    // });


    // Route::prefix('employee_contrats')->group(function () {
    Route::get('employee_contrats', [EmployeeContratController::class, 'index'])->name('employee.contrat.index');
    Route::get('employee_contrat-create', [EmployeeContratController::class, 'create'])->name('employee.contrat.create');
    Route::get('employee_contrat-edit/{employeeContrat}', [EmployeeContratController::class, 'edit'])->name('employee.contrat.edit');
    Route::put('employee_contrat-update/{employeeContrat}', [EmployeeContratController::class, 'update'])->name('employee.contrat.update');
    Route::post('employee_contrat-store', [EmployeeContratController::class, 'store'])->name('employee.contrat.store');
    Route::get('employee_contrat-delete/{employeeContrat}', [EmployeeContratController::class, 'delete'])->name('employee.contrat.delete');
    // });


    // Route::prefix('employee_payrolls')->group(function () {
    Route::get('contrat-payrolls', [EmployeePayrollController::class, 'index'])->name('employee.payroll.index');
    Route::get('contrat-payroll-create', [EmployeePayrollController::class, 'create'])->name('employee.payroll.create');
    Route::get('contrat-payroll-edit/{employeePayroll}', [EmployeePayrollController::class, 'edit'])->name('employee.payroll.edit');
    Route::put('contrat-payroll-update/{employeePayroll}', [EmployeePayrollController::class, 'update'])->name('employee.payroll.update');
    Route::post('contrat-payroll-store', [EmployeePayrollController::class, 'store'])->name('employee.payroll.store');
    Route::get('contrat-payroll-delete/{employeePayroll}', [EmployeePayrollController::class, 'delete'])->name('employee.payroll.delete');

    // });


    // Route::prefix('employee_timeoffs')->group(function () {
    Route::get('employee-timeoffs', [EmployeeTimeoffController::class, 'index'])->name('employee.timeoff.index');
    Route::get('employee-timeoff-create', [EmployeeTimeoffController::class, 'create'])->name('employee.timeoff.create');
    Route::get('employee-timeoff-edit/{employeeTimeoff}', [EmployeeTimeoffController::class, 'edit'])->name('employee.timeoff.edit');
    Route::put('employee-timeoff-update/{employeeTimeoff}', [EmployeeTimeoffController::class, 'update'])->name('employee.timeoff.update');
    Route::get('employee-timeoff-update-status', [EmployeeTimeoffController::class, 'updateStatus'])->name('employee.timeoff.update.status');
    Route::post('employee-timeoff-store', [EmployeeTimeoffController::class, 'store'])->name('employee.timeoff.store');
    Route::get('employee-timeoff-delete/{employeeTimeoff}', [EmployeeTimeoffController::class, 'delete'])->name('employee.timeoff.delete');
    Route::get('employee-my-timeoff', [EmployeeTimeoffController::class, 'myTimeOff'])->name('employee.timeoff.mytime');
    // });

    // Chats
    Route::get('/chat-bot', [HomeController::class, 'chat'])->name('chat.bot');
    Route::post('/chat-bot', [HomeController::class, 'getMessage'])->name('chat.getMessage');
    Route::post('/send-chat-bot', [HomeController::class, 'sendMessage'])->name('chat.sendMessage');
    Route::post('/check-chat-bot', [HomeController::class, 'checkMessage'])->name('chat.checkMessage');

    // Chats
    Route::put('/update-document/{employeeDocument}', [EmployeeDocumentController::class, 'update'])->name('document.update');
    Route::post('/store-document', [EmployeeDocumentController::class, 'store'])->name('document.store');
    Route::get('/delete-document/{employeeDocument}', [EmployeeDocumentController::class, 'delete'])->name('document.delete');



    // Route::prefix('documentation_categories')->group(function () {
    Route::get('/categorie-documentations', [DocumentationCategorieController::class, 'index'])->name('doc.categorie.index');
    Route::get('/getcategorie-doc', [DocumentationCategorieController::class, 'getcategoriedocs'])->name('doc.getcategoriedocs');
    Route::get('/get-docs-by-category/{doc_id}', [DocumentationCategorieController::class, 'getdocs'])->name('doc.getdocs');


    Route::get('/categorie-documentation-create', [DocumentationCategorieController::class, 'create'])->name('doc.categorie.create');
    Route::get('/categorie-documentation-edit/{documentationCategorie}', [DocumentationCategorieController::class, 'edit'])->name('doc.categorie.edit');
    Route::put('/categorie-documentation-update/{documentationCategorie}', [DocumentationCategorieController::class, 'update'])->name('doc.categorie.update');
    Route::post('/categorie-documentation-store', [DocumentationCategorieController::class, 'store'])->name('doc.categorie.store');
    Route::get('/categorie-documentation-delete/{documentationCategorie}', [DocumentationCategorieController::class, 'delete'])->name('doc.categorie.delete');
    // });


    // Route::prefix('docs')->group(function () {
    Route::get('/documents-delete-files', [DocController::class, 'getfiledelete'])->name('file.doc.delete');
    Route::get('/documents-recents', [DocController::class, 'getrecents'])->name('doc.recent');
    Route::get('/documents', [DocController::class, 'index'])->name('doc.index');
    Route::get('/documents/share-with-me', [DocController::class, 'doc_share'])->name('doc.share.with.me');
    Route::get('/documents/detail/{doc}', [DocController::class, 'detail_index'])->name('doc.detail.index');
    Route::get('/document-create', [DocController::class, 'create'])->name('doc.create');
    Route::get('/document-edit/{doc}', [DocController::class, 'edit'])->name('doc.edit');
    Route::post('/document-update', [DocController::class, 'update'])->name('doc.update');
    Route::post('/document-store', [DocController::class, 'store'])->name('doc.store'); //Nouvelle version
    Route::post('/document-store-fichier', [DocController::class, 'store_fichier'])->name('doc.file.store'); //nouveau fichier
    Route::post('/share-docs-role', [DocController::class, 'sharedocs'])->name('doc.file.share');
    Route::get('/document-all-version/{id}', [DocController::class, 'getAllVersion']);
    Route::get('/document-get-user/{id}', [DocController::class, 'getUserDoc']);
    Route::get('/document-delete/{doc}', [DocController::class, 'delete'])->name('doc.delete');
    Route::get('/document-supprimer/{doc}', [DocController::class, 'supprimer'])->name('doc.supprimer');

    // });

});
