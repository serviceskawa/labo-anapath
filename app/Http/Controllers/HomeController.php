<?php

namespace App\Http\Controllers;

use App\Events\ChatBotEvent;
use App\Models\Appointment;
use App\Models\chat;
use App\Models\Client;
use App\Models\Consultation;
use App\Models\Contrat;
use App\Models\DetailTestOrder;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Test;
use App\Models\TestOrder;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{

    protected $patients;
    protected $contrats;
    protected $tests;
    protected $invoices;
    protected $testOrders;
    protected $reports;
    protected $appointments;
    protected $setting;
    protected $users;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Patient $patients, Setting $setting, Contrat $contrats, Test $tests, Invoice $invoices, TestOrder $testOrders, Report $reports, Appointment $appointments, User $users)
    {
        $this->middleware(['auth','tfauth']);

        //constructeur de la class avec les attributs

        $this->patients = $patients;
        $this->contrats = $contrats;
        $this->tests = $tests;
        $this->users = $users;
        $this->invoices = $invoices;
        $this->testOrders = $testOrders;
        $this->reports = $reports;
        $this->appointments = $appointments;
        $this->setting = $setting;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);

        if (!getOnlineUser()->can('view-dashboard')) {
            return view('home');
        } else {
            // Date de début du mois actuel
            $dateDebutMoisActuel = Carbon::now()->startOfMonth();

            // Date de début du mois précédent
            $dateDebutMoisPrecedent = Carbon::now()->subMonth()->startOfMonth();


            //Patients
                // Remplacez "Patient" par le nom correct du modèle si nécessaire
                $valeurMoisActuelPatient = Patient::whereBetween('created_at', [$dateDebutMoisActuel, Carbon::now()])->count();
                $valeurMoisPrecedentPatient = Patient::whereBetween('created_at', [$dateDebutMoisPrecedent, $dateDebutMoisActuel->subDay()])->count();

                // Calcul de la croissance en pourcentage
                if ($valeurMoisPrecedentPatient !== 0) {
                    $croissance = (($valeurMoisActuelPatient - $valeurMoisPrecedentPatient) / $valeurMoisPrecedentPatient) * 100;
                } else {
                    $croissance = 0; // Pour éviter la division par zéro
                }

                $valeurPatient = Patient::count();
                $crPatient = number_format($croissance, 2);
            //Fin patient

            //Client Pro

                // Remplacez "Client" par le nom correct du modèle si nécessaire
                $valeurMoisActuel = Client::whereBetween('created_at', [$dateDebutMoisActuel, Carbon::now()])->count();
                $valeurMoisPrecedent = Client::whereBetween('created_at', [$dateDebutMoisPrecedent, $dateDebutMoisActuel->subDay()])->count();

                // Calcul de la croissance en pourcentage
                if ($valeurMoisPrecedent !== 0) {
                    $croissance = (($valeurMoisActuel - $valeurMoisPrecedent) / $valeurMoisPrecedent) * 100;
                } else {
                    $croissance = 0; // Pour éviter la division par zéro
                }

                $valeurClient = Client::count();
                $crClient = number_format($croissance, 2);
            //Fin client pro

            //DEMANDE D'EXAMEN
                $valeurMoisActuel = TestOrder::whereBetween('created_at', [$dateDebutMoisActuel, Carbon::now()])->count();
                $valeurMoisPrecedent = TestOrder::whereBetween('created_at', [$dateDebutMoisPrecedent, $dateDebutMoisActuel->subDay()])->count();

                // Calcul de la croissance en pourcentage
                if ($valeurMoisPrecedent !== 0) {
                    $croissance = (($valeurMoisActuel - $valeurMoisPrecedent) / $valeurMoisPrecedent) * 100;
                } else {
                    $croissance = 0; // Pour éviter la division par zéro
                }

                $valeurTestOrder = TestOrder::count();
                $crTestOrder = number_format($croissance, 2);
            //Fin  Demande d'examen

            //CHIFFRE D'AFFAIRE
                $valeurMoisActuel = Invoice::whereBetween('created_at', [$dateDebutMoisActuel, Carbon::now()])->sum('total');
                $valeurMoisPrecedent = Invoice::whereBetween('created_at', [$dateDebutMoisPrecedent, $dateDebutMoisActuel->subDay()])->sum('total');

                // Calcul de la croissance en pourcentage
                if ($valeurMoisPrecedent !== 0) {
                    $croissance = (($valeurMoisActuel - $valeurMoisPrecedent) / $valeurMoisPrecedent) * 100;
                } else {
                    $croissance = 0; // Pour éviter la division par zéro
                }

                $valeurInvoice = Invoice::sum('total');
                $crInvoice = number_format($croissance, 2);
            //Fin chiffre d'affaire

            //CHIFFRE D'AFFAIRE DES MOIS

                $today = Carbon::today();

                $beginingWeekCurrent = now()->startOfWeek(); // Récupérer le début de la semaine en cours
                $endingWeekCurrent = now()->endOfWeek(); // Récupérer la fin de la semaine en cours

                $beginingWeekLast = now()->startOfWeek()->subWeek(); // Récupérer le début de la semaine en passée
                $endingWeekLast = $beginingWeekLast->copy()->endOfWeek(); // Récupérer la fin de la semaine précédente

                $todayTest = now()->format('Y-m-d');

                // Total pour la semaine actuelle facture de vente
                $totalForCurrentWeek = $this->invoices
                    ->whereBetween('updated_at', [$beginingWeekCurrent, $endingWeekCurrent])
                    ->where('paid', '=', 1)
                    ->where(['status_invoice' => 0])
                    ->sum('total');
                // Total pour la semaine passée facture de vente
                $totalForLastWeek = $this->invoices
                    ->whereBetween('updated_at', [$beginingWeekLast, $endingWeekLast])
                    ->where('paid', '=', 1)
                    ->where(['status_invoice' => 0])
                    ->sum('total');
                $totalToday = $this->invoices
                    ->whereDate('updated_at', $todayTest)
                    ->where('paid',1)
                    ->where(['status_invoice'=>0])
                    ->sum('total');
            //Fin CHIFFRE D'AFFAIRE

            //Examen fréquent
                $examensDemandes =DB::table('detail_test_orders')
                ->select('test_id', 'test_name', DB::raw('COUNT(*) as total_demandes'))
                ->groupBy('test_id', 'test_name')
                ->orderByDesc('total_demandes')
                ->get();
            //Fin examen fréquent

            //Status test order
                $totalByStatus = TestOrder::join('reports', 'test_orders.id', '=', 'reports.test_order_id')
                ->groupBy('reports.status')
                ->select('reports.status', DB::raw('COUNT(*) as total'))
                ->get();
            //fin test order

            //Hopitaux
                $Hoptitals = TestOrder::join('reports', 'test_orders.id', '=', 'reports.test_order_id')
                ->groupBy('reports.status')
                ->select('reports.status', DB::raw('COUNT(*) as total'))
                ->get();
            //Fin hospitaux

            //Statistique des docteurs
                $doctorDatas = [];

                $doctorData =[
                    'doctor' => '',
                    'assigne' => 0,
                    'traite' => 0
                ];

                foreach (getUsersByRole('docteur') as $doctor)
                {
                    $doctorData['id'] = $doctor->id;
                    $doctorData['doctor'] = $doctor->lastname .' '. $doctor->firstname;

                    foreach ($this->testOrders->where('attribuate_doctor_id',$doctorData['id'])->get() as $key) {
                        $doctorData['assigne']++;
                    }
                    $data = $this->testOrders->where('attribuate_doctor_id',$doctorData['id'])
                                ->whereHas('report', function($query){
                                    $query->where('status',1);
                                })
                                ->get();
                    foreach ($data as $key) {
                        $doctorData['traite']++;
                    }
                    $doctorDatas [] = $doctorData;

                    $doctorData =[
                        'doctor' => '',
                        'assigne' => 0,
                        'traite' => 0
                    ];
                }
            //Fin statistique

            // Tests orders
                $now = Carbon::now();
                $today = now()->format('Y-m-d');
                $testOrdersToday = $this->reports->whereDate('updated_at', $today)->get();
                $loggedInUserIds = $this->users->where('is_connect',1)->whereDate('updated_at', '=', $now->toDateString())->get();
            // fin test orders

            //Données par docteur
                $testOrdersByDoctors = null;
                $testOrdersByDoctorCount = null;
                $totalByStatusForDoctor = null;
                $appointments = null;
                //total de demandes affectées à un docteur
                $testOrdersByDoctorCount = TestOrder::where('attribuate_doctor_id',Auth::user()->id)->count();

                //Demandes d'examen affectées à un docteur
                $testOrdersByDoctors = TestOrder::where('attribuate_doctor_id',Auth::user()->id)->get();
                $testOrdersByDoctorsToday = TestOrder::where('attribuate_doctor_id',Auth::user()->id)->whereHas('report', function($query) use($today) {
                    $query->whereDate('updated_at',$today);
                })->get();

                //Status des demandes affectées à un doctor
                $totalByStatusForDoctor = TestOrder::where('attribuate_doctor_id',Auth::user()->id)->join('reports', 'test_orders.id', '=', 'reports.test_order_id')
                    ->groupBy('reports.status')
                    ->select('reports.status', DB::raw('COUNT(*) as total'))
                    ->get();

                //Rendez-vous par doctor
                $appointments = Appointment::where('user_id',Auth::user()->id)->where('status','pending')->get();

            //Fin données



            //Nombre de examen demandé & CA
                $mois_souhaite = Carbon::now()->format('m'); // Le mois actuel.

                // Compter le nombre de prestations pour le mois donné
                $nombreTests = TestOrder::whereMonth('test_orders.created_at', $mois_souhaite)
                    ->join('detail_test_orders', 'test_orders.id', '=', 'detail_test_orders.test_order_id')
                    ->count();

                // Calculer le chiffre d'affaires pour le mois donné
                $c_a_tests = TestOrder::whereMonth('test_orders.created_at', $mois_souhaite)
                    ->join('detail_test_orders', 'test_orders.id', '=', 'detail_test_orders.test_order_id')
                    ->join('tests', 'detail_test_orders.test_id', '=', 'tests.id')
                    ->sum('tests.price');
                // Compter le total de patients par examen demandés pour le mois donné
                $totalPatientTest = TestOrder::whereMonth('test_orders.created_at', $mois_souhaite)
                    ->join('patients', 'test_orders.patient_id', '=', 'patients.id')
                    ->count();

                // Compter le nombre total de patients par examen demandé pour le mois donné
                $totalPatientsParTest = TestOrder::whereMonth('test_orders.created_at', $mois_souhaite)
                    ->join('detail_test_orders', 'test_orders.id', '=', 'detail_test_orders.test_order_id')
                    ->join('tests', 'detail_test_orders.test_id', '=', 'tests.id')
                    ->join('patients', 'test_orders.patient_id', '=', 'patients.id')
                    ->groupBy('tests.id','tests.name') // Grouper par l'ID de la prestation
                    ->select('tests.name', DB::raw('COUNT(patients.id) as totalPatients'))
                    ->get();
                    // dd($totalPatientTest,$totalPatientsParTest);

            //Fin

            // Total patient
                $totalDemandesParHopitalCount = TestOrder::whereMonth('test_orders.created_at', $mois_souhaite)
                    ->join('hospitals', 'test_orders.hospital_id', '=', 'hospitals.id')
                    // ->groupBy('hospitals.id')
                    ->select('hospitals.name as nom_hopital', DB::raw('COUNT(DISTINCT test_orders.patient_id) as total_patients'))
                    ->count();
                $totalDemandesParHopital = TestOrder::whereMonth('test_orders.created_at', $mois_souhaite)
                    ->join('hospitals', 'test_orders.hospital_id', '=', 'hospitals.id')
                    ->groupBy('hospitals.id','hospitals.name')
                    ->select('hospitals.name as nom_hopital', DB::raw('COUNT(DISTINCT test_orders.patient_id) as total_patients'))
                    ->get();
                $totalDemandesParMedecinCount = TestOrder::whereMonth('test_orders.created_at', $mois_souhaite)
                    ->join('doctors', 'test_orders.doctor_id', '=', 'doctors.id')
                    // ->groupBy('doctors.id')
                    ->select('doctors.name as nom_medecin', DB::raw('COUNT(DISTINCT test_orders.patient_id) as total_patients'))
                    ->count();
                $totalDemandesParMedecin = TestOrder::whereMonth('test_orders.created_at', $mois_souhaite)
                    ->join('doctors', 'test_orders.doctor_id', '=', 'doctors.id')
                    ->groupBy('doctors.id','doctors.name')
                    ->select('doctors.name as nom_medecin', DB::raw('COUNT(DISTINCT test_orders.patient_id) as total_patients'))
                    ->get();
                $totalDemandesParTypeCount = TestOrder::whereMonth('test_orders.created_at', $mois_souhaite)
                    ->join('type_orders', 'test_orders.type_order_id', '=', 'type_orders.id')
                    // ->groupBy('type_orders.id')
                    ->select('type_orders.title as nom_type', DB::raw('COUNT(DISTINCT test_orders.patient_id) as total_patients'))
                    ->count();
                $totalDemandesParType = TestOrder::whereMonth('test_orders.created_at', $mois_souhaite)
                    ->join('type_orders', 'test_orders.type_order_id', '=', 'type_orders.id')
                    ->groupBy('type_orders.id','type_orders.title')
                    ->select('type_orders.title as nom_type', DB::raw('COUNT(DISTINCT test_orders.patient_id) as total_patients'))
                    ->get();
                // dd($totalDemandesParMedecin, $totalDemandesParMedecinCount, $totalDemandesParHopital, $totalDemandesParType);
            //Fin



            return view('dashboardPlus',compact(
                'crPatient','valeurPatient',
                'crClient','valeurClient',
                'crTestOrder','valeurTestOrder',
                'crInvoice','valeurInvoice','testOrdersToday', 'loggedInUserIds',
                'totalForCurrentWeek', 'totalForLastWeek', 'totalToday',
                'examensDemandes','totalByStatus','doctorDatas',
                'testOrdersByDoctors','testOrdersByDoctorsToday','testOrdersByDoctorCount',
                'totalByStatusForDoctor', 'appointments',
                'nombreTests','c_a_tests','totalPatientTest',
                'totalDemandesParHopital','totalDemandesParMedecin','totalDemandesParType',
                'totalDemandesParHopitalCount','totalDemandesParMedecinCount','totalDemandesParTypeCount'
            ));
        }

    }

    public function invoiceByDay()
    {
        //Récupération des données pour la semaine actuelle

        // Obtenez la date de début de la semaine actuelle
        $startOfWeek = now()->startOfWeek();

        // Obtenez la date de fin de la semaine actuelle
        $endOfWeek = now()->endOfWeek();

        // Récupérez les factures payées pour la semaine actuelle
        $invoicesCurrentWeek = Invoice::where('paid', 1)
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->where(['status_invoice' => 0])
            ->get();

        //Récupération des données pour la semaine passée

        // Obtenez la date de début de la semaine passée
        $startOfLastWeek = now()->subWeek()->startOfWeek();

        // Obtenez la date de fin de la semaine passée
        $endOfLastWeek = now()->subWeek()->endOfWeek();

        // Récupérez les factures payées pour la semaine passée
        $invoicesLastWeek = Invoice::where('paid', 1)
            ->whereBetween('updated_at', [$startOfLastWeek, $endOfLastWeek])
            ->where(['status_invoice' => 0])
            ->get();

        //Calcul du total par jour pour chaque semaine

        $totalCurrentWeekByDay = $invoicesCurrentWeek->groupBy(function ($invoice) {
            return $invoice->updated_at->format('Y-m-d');
        })->map(function ($invoices) {
            return $invoices->sum('total');
        });
        $totalCurrentLastByDay = $invoicesLastWeek->groupBy(function ($invoice) {
            return $invoice->updated_at->format('Y-m-d');
        })->map(function ($invoices) {
            return $invoices->sum('total');
        });

        return response()->json(['current'=>$totalCurrentWeekByDay,'last'=>$totalCurrentLastByDay]);


    }
    public function testorderStatus()
    {


        $invoicePaid = Invoice::where('status_invoice',0)->where('paid',1)->count();
        $invoiceTotalPaid = Invoice::where('status_invoice',0)->where('paid',1)->sum('total');

        $invoiceNoPaid = Invoice::where('status_invoice',0)->where('paid',0)->count();
        $invoiceTotalNoPaid = Invoice::where('status_invoice',0)->where('paid',0)->sum('total');

        $refundPaid = Invoice::where('status_invoice',1)->where('paid',1)->count();
        $refundTotalPaid = Invoice::where('status_invoice',1)->where('paid',1)->sum('total');

        $refundNoPaid = Invoice::where('status_invoice',1)->where('paid',0)->count();
        $refundTotalNoPaid = Invoice::where('status_invoice',1)->where('paid',0)->sum('total');

        return response()->json([
            'invoicePaid'=>$invoicePaid,'invoiceNoPaid'=>$invoiceNoPaid,
            'refundPaid'=>$refundPaid,'refundNoPaid'=>$refundNoPaid,
            'invoiceTotalPaid'=>(int)$invoiceTotalPaid,'invoiceTotalNoPaid'=>(int)$invoiceTotalNoPaid,
            'refundTotalPaid'=>(int)$refundTotalPaid,'refundTotalNoPaid'=>(int)$refundTotalNoPaid
        ]);


    }

    public function dashboard()
    {
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);

        $patients = Patient::all()->count();
        $contrats = Contrat::all()->count();
        $tests = Test::all()->count();

        $curmonth = now()->format('m'); // Récupérer le mois en cours sous forme de chiffre (ex : '01' pour janvier)
        $totalMonth = Invoice::whereMonth('created_at', $curmonth)->sum('total');

        $today = now()->format('Y-m-d'); // Récupérer la date d'aujourd'hui au format 'YYYY-MM-DD'
        $totalToday = Invoice::whereDate('created_at', $today)->sum('total');


        $testOrdersCount = $this->testOrders->all()->count();
        $testOrders = TestOrder::all();
        $noFinishTest = 0;
        $finishTest = 0;
        foreach ($testOrders as $testOrder) {
            if ($testOrder->report->is_deliver == 0) {
                $noFinishTest ++;
            }else {
                $finishTest++;
            }
        }

        $testOrdersToday = Report::whereDate('updated_at', $today)->get();

        $invoice = Invoice::all()->sum('total');

        $Appointments = Appointment::whereDate('date',$today)->get();

        $loggedInUserId = [];
        $loggedInUserIds = [];

        // Vérifier si l'ID est stocké dans la session
        if (session()->has('user_id')) {
            $loggedInUserId = session()->get('user_id');
            $loggedInUserIds[] = $loggedInUserId;
        }

       // dd($sessions);
        return view('dashboard', compact('patients', 'contrats', 'tests', 'totalToday', 'totalMonth',
        'testOrdersCount','noFinishTest','finishTest','Appointments', 'loggedInUserIds',
        'testOrdersToday','invoice'));
    }

    public function chat()
    {
       $users = User::all();
       $usersWithMessage = User::whereIn('id', function($query) {
        $query->select('sender_id')
            ->from('chats')
            ->where('receve_id', Auth::user()->id)
            ->whereNotNull('sender_id')
            ->distinct()
            ->union(
                DB::table('chats')
                    ->select('receve_id')
                    ->where('sender_id', Auth::user()->id)
                    // ->where('branch_id', session()->get('selected_branch_id'))
                    ->whereNotNull('receve_id')
                    ->distinct()
            );
        })->get();

        return view('chat.index',compact('users','usersWithMessage'));
    }

    public function getMessage(Request $request)
    {
        $sender_id = Auth::user()->id;
        $recever_id = $request->recever_id;
        $sender = User::find($sender_id);
        $receve = User::find($recever_id);

        // Récupérez les messages pour affichage
        $message_count = Chat::where(function ($query) use ($sender_id, $recever_id) {
                $query->where('sender_id', $sender_id)->where('receve_id', $recever_id);
            })->orWhere(function ($query) use ($sender_id, $recever_id) {
                $query->where('sender_id', $recever_id)->where('receve_id', $sender_id);
            })->orderBy('created_at', 'asc')
        ->count();

        $messages = Chat::where(function ($query) use ($sender_id, $recever_id) {
                $query->where('sender_id', $sender_id)->where('receve_id', $recever_id);
            })->orWhere(function ($query) use ($sender_id, $recever_id) {
                $query->where('sender_id', $recever_id)->where('receve_id', $sender_id);
            })->orderBy('created_at', 'asc')
        ->get();

        chat::where('receve_id',$sender_id)->update(['read'=>1]);

        // $forMessageRead = chat::where('receve_id',$sender_id)->get();
        // foreach ($forMessageRead as $value) {
        //     if ($value->read == 0) {
        //         $chat = chat::find($value->id);
        //         $chat->fill(['read',1);
        //     }
        // }
        if ( $message_count) {
            return response()->json(['message'=>'old','content_message'=>$messages,'user_connect'=>$sender_id,'sender'=>$sender,'receve'=>$receve]);
        }else{
            chat::create([
                'sender_id'=> $sender_id,
                'receve_id' => $recever_id,
                'status' =>0
            ]);
            return response()->json(['message'=>'new','sender'=>$sender,'receve'=>$receve]);
        }
    }


    public function sendMessage(Request $request)
    {
        $sender_id = Auth::user()->id;
        $recever_id = $request->receve_id;
        $message = $request->message;

        $sender = User::find($sender_id);
        $receve = User::find($recever_id);
        // dd($request);
            $chat = chat::create([
                'sender_id'=> $sender_id,
                'receve_id' => $recever_id,
                'message' => $message,
                'status' =>1,
                'read' => 0
            ]);

            // Récupérez les messages pour affichage
            $message_count = Chat::where(function ($query) use ($sender_id, $recever_id) {
                    $query->where('sender_id', $sender_id)->where('receve_id', $recever_id);
                })->orWhere(function ($query) use ($sender_id, $recever_id) {
                    $query->where('sender_id', $recever_id)->where('receve_id', $sender_id);
                })->orderBy('created_at', 'asc')
            ->count();
            if ($message_count>1) {
                if ($request->old != 0) {
                    return response()->json(['id'=>$chat->id,'sender'=>$sender,'receve'=>$receve]);
                }else {
                    return back()->with('error',"Cette discussion existe déjà");
                }
            }else{
                return back()->with('sucess',"Nouvelle discussion entamée");
            }



    }

    public function checkMessage(Request $request)
    {
        $message = $request->message;
        $recever_id = $request->receve_id;
        $sender_id = Auth::user()->id;

        $messageNew = chat::where('message',$message)->where(function ($query) use ($sender_id, $recever_id) {
            $query->where('sender_id', $sender_id)->where('receve_id', $recever_id);
        })->orWhere(function ($query) use ($sender_id, $recever_id) {
            $query->where('sender_id', $recever_id)->where('receve_id', $sender_id);
        })->orderBy('created_at', 'asc')->first();

        // Récupérez les nouveaux messages depuis la base de données en fonction de $lastMessageId
        $newMessages = Chat::where('id', '>', $messageNew->id)->orderBy('created_at', 'asc')->get();

        // Retournez les nouveaux messages au format JSON
        return response()->json(['messages' => $newMessages,'receve_id'=>$request->receve_id]);
    }
}
