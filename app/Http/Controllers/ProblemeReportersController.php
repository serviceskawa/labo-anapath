<?php

namespace App\Http\Controllers;

use App\Models\ProblemCategory;
use App\Models\ProblemReport;
use App\Models\Setting;
use App\Models\TestOrder;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProblemeReportersController extends Controller
{

    protected $setting;
    protected $problemReport;
    protected $problemCategory;
    protected $testOrder;
    protected $tickets;
    protected $ticket_comments;

    public function __construct(Setting $setting, ProblemReport $problemReport, ProblemCategory $problemCategory, TestOrder $testOrder,Ticket $tickets, TicketComment $ticket_comments)
    {
        $this->setting = $setting;
        $this->problemReport = $problemReport;
        $this->problemCategory = $problemCategory;
        $this->testOrder = $testOrder;
        $this->tickets = $tickets;
        $this->ticket_comments = $ticket_comments;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $setting = $this->setting->find(1);
        $problemReports = $this->problemReport->latest()->get();
        $problemCategories = $this->problemCategory->latest()->get();

        // $user_role = User::find(Auth::user()->id)->userCheckRole('rootuser');

        if (getOnlineUser()->can('view-process-cashbox-tickets')) {
            $tickets = Ticket::latest()->get();
        }else {
            $tickets = Ticket::where('user_id',Auth::user()->id)->latest()->get();
        }

        return view('errors_reports.index', compact('setting', 'tickets','problemReports','problemCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $testOrders = $this->testOrder->all();
        $problemCategories = $this->problemCategory->latest()->get();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('errors_reports.create', compact('testOrders','problemCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request,[
            'subject' => 'required',
            'description' => 'nullable',
        ]);
        $code = generateCodeBillet();

        // $test_order = TestOrder::where('code',$data['test_order_code'])->first();
        try {
            $this->tickets->create([
                'subject'=>$data['subject'],
                'user_id'=>Auth::user()->id,
                'ticket_code'=>$code,
                'description'=>$data['description'],
            ]);
            return redirect()->route('probleme.report.index')->with('success',"Ticket enregistrée avec success");
        } catch (\Throwable $th) {
            return back()->with('error',"Un problème est suvenu lors de l'enrégistrement".$th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProblemReport  $problemReport
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $problemReport = $this->problemReport->find($id);
        return response()->json($problemReport);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProblemReport  $problemReport
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ticket = $this->tickets->find($id);
        $ticket_comments = $this->ticket_comments->where('ticket_id',$id)->get();
        foreach ($ticket_comments as $key => $value) {
            if ($value->user_id !=Auth::user()->id) {
                // $value->read = 1;
                // $value->save();
                // $ticket->status = "ouvert";
                // $ticket->save();
            }
        }
        $user_role = User::find(Auth::user()->id)->userCheckRole('rootuser');
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('errors_reports.edit', compact('ticket','ticket_comments','user_role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProblemReport  $problemReport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $this->validate($request,[
            'status'=>'nullable',
            'id'=>'required',
            'subject' => 'nullable'
        ]);

        try {
            $problemReport = $this->tickets->find($data['id']);
            //Si l'utilisateur connecté n'est pas l'auteur du ticket il ne peut pas modifier ça
            $user_role = User::find(Auth::user()->id)->userCheckRole('rootuser');

            if (!$user_role) {
                if ($problemReport->user_id != Auth::user()->id) {
                    return back()->with('error',"Vous n'êtes pas l'auteur de ce ticket");
                }else {
                    # code...
                    $problemReport->update([
                        'status'=>$data['status'] ? $data['status'] : $problemReport->status,
                        'subject'=>$data['subject']
                    ]);
                    // return response()->json($data['status'],200);
                    return back()->with('success',"Mis à jour effectué avec success");
                }
            }else {
                 # code...
                 $problemReport->update([
                    'status'=>$data['status'] ? $data['status'] : $problemReport->status,
                    'subject'=>$data['subject']
                ]);
                // return response()->json($data['status'],200);
                return back()->with('success',"Mis à jour effectué avec success");
            }
        } catch (\Throwable $th) {
            // return response()->json(500);
            return back()->with('error',"Une error subvenue lors de le mis à jour".$th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProblemReport  $problemReport
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $problemReport = $this->tickets->find($id);
        $problemReport->delete();
        return redirect()->route('probleme.report.index')->with('success',"Suppression éffectuée avec success");
    }

    public function sendMessage(Request $request)
    {

        $sender_id = Auth::user()->id;
        $message = $request->message;
        $sender = User::find($sender_id);
        $ticket = $this->tickets->find($request->ticket_id);
        $last_comment = TicketComment::latest()->first();
        $is_read = false;
        if ($last_comment) {
            if ($last_comment->user_id != $sender_id) {
                $last_comment->read = 1;
                $last_comment->save();
                $ticket->status = "ouvert";
                $ticket->save();
                $is_read = true;
            }
        }

        $comment = TicketComment::create([
            'ticket_id' => $request->ticket_id,
            'user_id' => $sender_id,
            'comment' => $message,
            'read' => 0
        ]);

        if ($ticket->user_id != $sender_id) {
            $ticket->status = "repondu";
            $ticket->save();
        }

        // $recever_id = $request->receve_id;

        // $receve = User::find($recever_id);
        // // dd($request);
        //     $chat = chat::create([
        //         'sender_id'=> $sender_id,
        //         'receve_id' => $recever_id,
        //         'message' => $message,
        //         'status' =>1,
        //         'read' => 0
        //     ]);

        //     // Récupérez les messages pour affichage
        //     $message_count = Chat::where(function ($query) use ($sender_id, $recever_id) {
        //             $query->where('sender_id', $sender_id)->where('receve_id', $recever_id);
        //         })->orWhere(function ($query) use ($sender_id, $recever_id) {
        //             $query->where('sender_id', $recever_id)->where('receve_id', $sender_id);
        //         })->orderBy('created_at', 'asc')
        //     ->count();
        //     if ($message_count>1) {
        //         if ($request->old != 0) {
        //             return response()->json(['id'=>$chat->id,'sender'=>$sender,'receve'=>$receve]);
        //         }else {
        //             return back()->with('error',"Cette discussion existe déjà");
        //         }
        //     }else{
        //         return back()->with('sucess',"Nouvelle discussion entamée");
        //     }

        return response()->json(['comment'=>$comment,'sender'=>$sender,'is_read'=>$is_read]);

    }
}
