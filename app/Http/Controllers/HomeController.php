<?php

namespace App\Http\Controllers;

use App\Events\ChatBotEvent;
use App\Models\Appointment;
use App\Models\chat;
use App\Models\Consultation;
use App\Models\Contrat;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Prestation;
use App\Models\PrestationOrder;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Test;
use App\Models\TestOrder;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

            Log::info('cc je teste le log pour voir');

            $patients = $this->patients->all()->count();
            $contrats = $this->contrats->all()->count();
            $tests = $this->tests->all()->count();

            //Mois courant
            $curmonth = now()->format('m'); // Récupérer le mois en cours sous forme de chiffre (ex : '01' pour janvier)
            $annuletotalMonth = $this->invoices->whereMonth('updated_at', $curmonth)->where('paid','=',1)->where(['status_invoice'=>1])->sum('total');
            $totalMonth = $this->invoices->whereMonth('updated_at', $curmonth)->where('paid','=',1)->where(['status_invoice'=>0])->sum('total') - $annuletotalMonth;
            $noannuletotalMonth = $this->invoices->whereMonth('updated_at', $curmonth)->where('paid','=',0)->where(['status_invoice'=>1])->sum('total');
            $nototalMonth = $this->invoices->whereMonth('updated_at', $curmonth)->where('paid','=',0)->where(['status_invoice'=>0])->sum('total');

            //Mois précédent
            $now = Carbon::now();
            $lastMonth = $now->copy()->subMonth()->format('m');
            $annuletotalLastMonth = $this->invoices->whereMonth('updated_at', $lastMonth)->where('paid','=',1)->where(['status_invoice'=>1])->sum('total');
            $totalLastMonth = $this->invoices->whereMonth('updated_at', $lastMonth)->where('paid','=',1)->where(['status_invoice'=>0])->sum('total') - $annuletotalLastMonth;
            $noannuletotalLastMonth = $this->invoices->whereMonth('updated_at', $lastMonth)->where('paid','=',0)->where(['status_invoice'=>1])->sum('total');
            $nototalLastMonth = $this->invoices->whereMonth('updated_at', $lastMonth)->where('paid','=',0)->where(['status_invoice'=>0])->sum('total');

            //Jour actuellement
            $today = now()->format('Y-m-d'); // Récupérer la date d'aujourd'hui au format 'YYYY-MM-DD'
            $annuletotalToday = $this->invoices->whereDate('updated_at', $today)->where('paid','=',1)->where(['status_invoice'=>1])->sum('total');
            $totalToday = $this->invoices->whereDate('updated_at', $today)->where('paid','=',1)->where(['status_invoice'=>0])->sum('total') - $annuletotalToday;
            $noannuletotalToday = $this->invoices->whereDate('updated_at', $today)->where('paid','=',0)->where(['status_invoice'=>1])->sum('total');
            $nototalToday = $this->invoices->whereDate('updated_at', $today)->where('paid','=',0)->where(['status_invoice'=>0])->sum('total');



            //plus de 3 semaines
            $threeWeeksAgo = Carbon::now()->subWeeks(3);
            $weekTest = $this->testOrders->whereDate('created_at', '<', $threeWeeksAgo)->get();


            $testOrdersCount = $this->testOrders->all()->count();
            $testOrders = $this->testOrders->all();
            $noFinishTest = 0;
            $noFinishWeek = 0;
            $finishTest = 0;
            $noSaveTest = 0;
            foreach ($testOrders as $testOrder) {
                if ($testOrder->report !=null) {

                    if ($testOrder->report->is_deliver == 0) {
                        $noFinishTest ++;

                    }else {
                        $finishTest++;
                    }
                }else
                {
                    $noSaveTest++;
                }
            }
            $weekTests = $this->testOrders->whereDate('created_at', '>', $threeWeeksAgo)->get();
            foreach ($weekTests as $testOrder) {
                if ($testOrder->report !=null) {

                    //$weekTest[] = $testOrder->whereDate('updated_at', '<', $threeWeeksAgo)->get();
                    if ($testOrder->report->is_deliver == 0) {
                        $noFinishWeek ++;
                    }
                }
            }

            $testOrdersToday = $this->reports->whereDate('updated_at', $today)->get();

            $invoice = $this->invoices->all()->sum('total');

            $Appointments = $this->appointments->whereDate('date',$today)->get();


            $loggedInUserIds = $this->users->where('is_connect',1)->whereDate('updated_at', '=', $now->toDateString())->get();



            //Statistiques par médecin

            // $doctorDatas =[
            //     // 'doctor' => '',
            //     // 'totalDay' => 0,
            //     // 'lastMonth' => 0,
            //     // 'curmonth' => 0
            // ];

            // $doctorData =[
            //     'doctor' => '',
            //     'totalDay' => 0,
            //     'lastMonth' => 0,
            //     'curmonth' => 0
            // ];

            // foreach (getUsersByRole('docteur') as $doctor)
            // {

            //     $doctorData['id'] = $doctor->id;
            //     $doctorData['doctor'] = $doctor->lastname .' '. $doctor->firstname;

            //     foreach ($this->invoices->whereDate('updated_at', $today)->where('paid','=',1)->get() as $invoice) {
            //         if ($invoice->order->attribuateToDoctor) {
            //             if ($invoice->order->attribuateToDoctor->id == $doctorData['id']) {
            //                 $doctorData['totalDay'] += $invoice->total;
            //             }
            //         }
            //     }

            //     foreach ($this->invoices->whereDate('updated_at', $lastMonth)->where('paid','=',1)->get() as $invoice) {
            //         if ($invoice->order->attribuateToDoctor) {
            //             if ($invoice->order->attribuateToDoctor->id == $doctorData['id']) {
            //                 $doctorData['lastMonth'] += $invoice->total;
            //             }
            //         }
            //     }

            //     foreach ($this->invoices->whereDate('updated_at', $curmonth)->where('paid','=',1)->get() as $invoice) {
            //         if ($invoice->order->attribuateToDoctor) {
            //             if ($invoice->order->attribuateToDoctor->id == $doctorData['id']) {
            //                 $doctorData['curmonth'] += $invoice->total;
            //             }
            //         }
            //     }

            //     $doctorDatas [] = $doctorData;

            //     $doctorData =[
            //         'doctor' => '',
            //         'totalDay' => 0,
            //         'lastMonth' => 0,
            //         'curmonth' => 0
            //     ];

            // }


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



            // dd($doctors);

            // dd($loggedInUserIds);

        // dd($sessions);
            return view('dashboard', compact('patients', 'contrats', 'tests', 'totalToday', 'annuletotalToday','noannuletotalToday',
            'totalMonth', 'annuletotalMonth', 'noannuletotalMonth','totalLastMonth', 'annuletotalLastMonth', 'noannuletotalLastMonth',
            'testOrdersCount','noFinishTest', 'noSaveTest','noFinishWeek','finishTest','Appointments',
            'loggedInUserIds','nototalToday', 'nototalMonth', 'nototalLastMonth', 'testOrdersToday','invoice','doctorDatas'));
        }

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
        return view('chat.index',compact('users'));
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
        })->orderBy('created_at', 'asc')->count();

        $messages = Chat::where(function ($query) use ($sender_id, $recever_id) {
            $query->where('sender_id', $sender_id)->where('receve_id', $recever_id);
        })->orWhere(function ($query) use ($sender_id, $recever_id) {
            $query->where('sender_id', $recever_id)->where('receve_id', $sender_id);
        })->orderBy('created_at', 'asc')->get();

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
        $chat = chat::create([
            'sender_id'=> $sender_id,
            'receve_id' => $recever_id,
            'message' => $message,
            'status' =>1
        ]);
        return response()->json(['id'=>$chat->id]);

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
