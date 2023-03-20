<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
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
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        if (!getOnlineUser()->can('view-dashboard')) {
            return view('home');
        } else {
            $patients = Patient::all()->count();
            $contrats = Contrat::all()->count();
            $tests = Test::all()->count();

            $curmonth = now()->format('m'); // Récupérer le mois en cours sous forme de chiffre (ex : '01' pour janvier)
            $totalMonth = Invoice::whereMonth('created_at', $curmonth)->sum('total');

            $today = now()->format('Y-m-d'); // Récupérer la date d'aujourd'hui au format 'YYYY-MM-DD'
            $totalToday = Invoice::whereDate('created_at', $today)->sum('total');


            $testOrdersCount = TestOrder::all()->count();
            $testOrders = TestOrder::all();
            $noFinishTest = 0;
            $finishTest = 0;
            foreach ($testOrders as $testOrder) {
                if ($testOrder->report !=null) {
                    if ($testOrder->report->is_deliver == 0) {
                        $noFinishTest ++;
                    }else {
                        $finishTest++;
                    }
                }
            }

            $testOrdersToday = Report::whereDate('updated_at', $today)->get();

            $invoice = Invoice::all()->sum('total');

            $appointements = Appointment::whereDate('date',$today)->get();


            $loggedInUserIds = [];

            // Vérifier si l'ID est stocké dans la session
            if (session()->has('user_id')) {
                $loggedInUserIds[] = session()->get('user_id');
            }

        // dd($sessions);
            return view('dashboard', compact('patients', 'contrats', 'tests', 'totalToday', 'totalMonth',
            'testOrdersCount','noFinishTest','finishTest','appointements', 'loggedInUserIds',
            'testOrdersToday','invoice'));
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


        $testOrdersCount = TestOrder::all()->count();
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

        $appointements = Appointment::whereDate('date',$today)->get();


        $loggedInUserIds = [];

        // Vérifier si l'ID est stocké dans la session
        if (session()->has('user_id')) {
            $loggedInUserIds[] = session()->get('user_id');
        }

       // dd($sessions);
        return view('dashboard', compact('patients', 'contrats', 'tests', 'totalToday', 'totalMonth',
        'testOrdersCount','noFinishTest','finishTest','appointements', 'loggedInUserIds',
        'testOrdersToday','invoice'));
    }
}
