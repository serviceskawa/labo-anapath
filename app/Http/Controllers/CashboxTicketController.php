<?php

namespace App\Http\Controllers;

use App\Models\CashboxTicket;
use App\Models\CashboxTicketDetail;
use App\Models\Setting;
use App\Models\Supplier;
use Illuminate\Http\Request;

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

        try {

            // $cashboxAdd = CashboxAdd::find($cashboxAddData['id']);
            $this->tickets->create([
                'cashbox_id' => $data['cashbos_id'],
                'supplier_id' => $data['supplier_id'],
                'description' => $data['description']
            ]);
            return back()->with('success', "Bon de caisse enregistré ");

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
            'item_id' => 2,
            // 'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'line_amount' => $request->unit_price*$request->quantity,
        ];

        try {

            // $cashboxAdd = CashboxAdd::find($cashboxAddData['id']);
            $this->cashboxTicketDetail->create([
                'cashbox_ticket_id' => $data['cashbox_ticket_id'],
                'item_name' => $data['item_name'],
                'item_id' => $data['item_id'],
                'quantity' => $data['quantity'],
                'unit_price' => $data['unit_price'],
                'line_amount' => $data['line_amount']
            ]);
            $ticket = $this->tickets->find($data['cashbox_ticket_id']);
            $ticket->amount += $data['line_amount'];
            $ticket->save();
            return back()->with('success', "Bon de caisse enregistré");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    public function destroy($id)
    {
        // if (!getOnlineUser()->can('delete-banks')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $this->tickets->find($id)->delete();
        return back()->with('success', "Un élement a été supprimé ! ");
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
}
