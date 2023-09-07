<?php

namespace App\Http\Controllers;

use App\Models\Cashbox;
use App\Models\CashboxAdd;
use App\Models\CashboxTicket;
use App\Models\CashboxTicketDetail;
use App\Models\Setting;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashboxTicketController extends Controller
{
    protected $setting;
    protected $tickets;
    protected $suppliers;
    protected $cashboxTicketDetail;

    public function __construct(Setting $setting, CashboxTicket $tickets, Supplier $suppliers, CashboxTicketDetail $cashboxTicketDetail)
    {
        $this->setting = $setting;
        $this->tickets = $tickets;
        $this->suppliers = $suppliers;
        $this->cashboxTicketDetail = $cashboxTicketDetail;
    }

    public function index()
    {
        // if (!getOnlineUser()->can('view-cashboxs')) {
        //     return back()->with('error',"Vous n\'êtes pas autorisé");
        // }

        $tickets = $this->tickets->latest()->get();
        $suppliers = $this->suppliers->latest()->get();
        $setting = $this->setting->find(1);
        config(['app.name'=>$setting->titre]);
        return view('cashbox.ticket.index',compact(['tickets','suppliers']));
    }
    public function detail_index($id)
    {
        // if (!getOnlineUser()->can('view-cashboxs')) {
        //     return back()->with('error',"Vous n\'êtes pas autorisé");
        // }

        $ticket = $this->tickets->find($id);
        $suppliers = $this->suppliers->latest()->get();
        $setting = $this->setting->find(1);
        config(['app.name'=>$setting->titre]);
        return view('cashbox.ticket.details.index',compact(['ticket','suppliers']));
    }

    public function store(Request $request)
    {
        // if (!getOnlineUser()->can('view-cashboxs')) {
        //     return back()->with('error',"Vous n\'êtes pas autorisé");
        // }
        $data = [
            'cashbos_id' => $request->cashbos_id,
            'supplier_id' => $request->supplier_id,
            'description' => $request->description
        ];
        $code = generateCodeTicket();

        try {

            // $cashboxAdd = CashboxAdd::find($cashboxAddData['id']);
            $ticket = $this->tickets->create([
                'code' => $code,
                'cashbox_id' => 2,
                'supplier_id' => $data['supplier_id'],
                'description' => $data['description']
            ]);
            // return back()->with('success', "Bon de caisse enregistré ");
            return redirect()->route('cashbox.ticket.details.index',$ticket->id);

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    public function updateTotal(Request $request)
    {
        // if (!getOnlineUser()->can('edit-test-orders')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $ticket = $this->tickets->findorfail($request->ticket_id);

        $ticket->fill([
            "amount" => $request->amount,
        ])->save();

        return response()->json($ticket);
    }

    public function update(Request $request)
    {
        // if (!getOnlineUser()->can('view-cashboxs')) {
        //     return back()->with('error',"Vous n\'êtes pas autorisé");
        // }
        $data = [
            'id' => $request->ticket_id,
            'supplier_id' => $request->supplier_id,
            'description' => $request->description
        ];

        try {

            // $cashboxAdd = CashboxAdd::find($cashboxAddData['id']);
            $ticket = $this->tickets->find($data['id']);
            $ticket->update([
                'cashbox_id' => 2,
                'supplier_id' => $data['supplier_id'],
                'description' => $data['description']
            ]);
            // return back()->with('success', "Bon de caisse enregistré ");
            return back()->with('success',"Mis à jour effectué");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    public function detail_store(Request $request)
    {
        // if (!getOnlineUser()->can('view-cashboxs')) {
        //     return back()->with('error',"Vous n\'êtes pas autorisé");
        // }
        $data = [
            'cashbox_ticket_id' => $request->cashbox_ticket_id,
            'item_name' => $request->item_name,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'line_amount' => $request->unit_price*$request->quantity,
        ];

        // return response()->json($data);
        $article = $this->cashboxTicketDetail->where('item_name','=',$data['item_name'])->first();

        try {


            DB::transaction(function () use ($data, $article) {
                $details = new CashboxTicketDetail();
                $details->cashbox_ticket_id = $data['cashbox_ticket_id'];
                $details->item_name = $data['item_name'];
                $details->item_id = $article ? $article->id:null;
                $details->unit_price = $data['unit_price'];
                $details->quantity = $data['quantity'];
                $details->line_amount = $data['line_amount'];
                $details->save();
            });
            $ticket = $this->tickets->find($data['cashbox_ticket_id']);
            $ticket->amount += $data['line_amount'];
            $ticket->save();
            return response()->json($ticket,200);
            // return back()->with('success', "Bon de caisse enregistré");

        } catch(\Throwable $ex){
            return response()->json("Échec de l'enregistrement ! " .$ex->getMessage(),500);
        }
    }

    public function getTicketDetail($id)
    {
        $ticket = $this->tickets->find($id);
        $details = $this->cashboxTicketDetail->where('cashbox_ticket_id', $ticket->id)->get();
        return response()->json($details);
    }

    public function destroy($id)
    {
        // if (!getOnlineUser()->can('delete-banks')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        try {
            $this->tickets->find($id)->delete();
            return back()->with('success', "    Un élement a été supprimé ! ");
        } catch(\Throwable $ex){
            return back()->with('error', "Impossible de supprimer cet élément !  Celui-ci est lié à d'autres éléments. Pour effectuer cette suppression, vous devez d'abord supprimer ou mettre à jour les éléments liés dans d'autres tables.");
        }
    }

    public function detail_destroy($id)
    {
        // if (!getOnlineUser()->can('delete-banks')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $detail = $this->cashboxTicketDetail->find($id);
        $ticket = $this->tickets->find($detail->cashbox_ticket_id);
        $ticket->amount -= $detail->line_amount;
        $ticket->save();
        $detail->delete();
        return back()->with('success', "Un élement a été supprimé !");
    }

    public function updateStatus(Request $request)
    {
        $ticket = $this->tickets->find($request->id);
        return response()->json($request->status);
    }

    public function updateTicketStatus(Request $request)
    {
       $ticket = $this->tickets->find($request->input('id'));
       $status = $request->input('status');
       $ticket->fill([
        'status' => $status
       ])->save();
       if ($status == "approuve") {
        $cash = Cashbox::find(2);
        $cash->current_balance -= $ticket->amount;
        $cash->save();

        CashboxAdd::create([
            'cashbox_id' => 2,
            'date' => Carbon::now(),
            'amount' => $ticket->amount,
            'user_id' => Auth::user()->id
        ]);
       }
       return back()->with('success',"Bon de caisse ".$status." avec succès");
    }
}
