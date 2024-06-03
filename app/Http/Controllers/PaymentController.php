<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SettingApp;


class PaymentController extends Controller
{
    protected $settingApp;

    public function __construct(SettingApp $settingApp)
    {
        $this->settingApp = $settingApp;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function storejs(Request $request)
    {
        
        // Code pour connaitrele moyen de paiement utilise affin d'initier le paiement par MTN MOBILE MONEY
        $res_pay = "Test" ;
        $msg = 'N/A';
        $status = 'N/A';
        $transaction_id = 'N/A';
        $response_transaction = '';
        
            // Paiement MTN MOBILE MONEY
            $client = new Client();
            $token_payment = $this->settingApp->where('key','token_payment')->first();
            
            
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => $token_payment->value
            ];
            
            // Assurez-vous que les valeurs récupérées sont correctes
            $payment_number = strval($request->get('payment_number'));
            $amount_payer = strval($request->get('amount_payer'));
             
            
            if (empty($payment_number) || empty($amount_payer)) {
                return response()->json(['error' => 'Les champs numero_de_telephone et amount_payer sont requis.'], 400);
            }

            $invoice_code_examen = Invoice::find($request->invoice_id);
            
            $body = json_encode([
                "tel" => "229".$payment_number,
                "amount" =>  $amount_payer,
                "description" => "CAAP : ". $invoice_code_examen->order->code .". Frais : ".$request->get('fee')
            ]);

            
            try {

                if($request->payment_method == "MOBILEMONEY-MTN")
                {
                    $guzzleRequest = new GuzzleRequest('POST', 'https://pay.sckaler.cloud/api/collection/mtn', $headers, $body);

                }
                elseif($request->payment_method == "MOBILEMONEY-MOOV")
                {
                    $guzzleRequest = new GuzzleRequest('POST', 'https://pay.sckaler.cloud/api/collection/moov', $headers, $body);
                }
                
                $response = $client->send($guzzleRequest);
                
                                        // return response()->json(['message' => 'OK'], 200);
                $res_pay = $response->getBody()->getContents();
                
                // recuperation des messages de reponses en attente 
                $res_pay_array = json_decode($res_pay, true);
                $msg = $res_pay_array['msg'] ?? 'N/A';
                $status = $res_pay_array['status'] ?? 'N/A';
                $transaction_id = $res_pay_array['transaction_id'] ?? 'N/A';
                
                // Paiement initier avec  succes
                if($status == "SUCCESS")
                {
                    DB::beginTransaction();

                    try {
                        $checkInvoiceId = Payment::where('invoice_id', $request->invoice_id)->first();
                    
                        if ($checkInvoiceId && $checkInvoiceId->status != "SUCCESS") {
                            $payment = $checkInvoiceId;
                        } else {
                            $payment = new Payment();
                        }
                    
                        $payment->payment_name = $request->payment_method;
                        $payment->payment_number = $request->payment_number;
                        $payment->payment_status = "INITIATED";
                        $payment->payment_amount = $amount_payer;
                        $payment->payment_id = $transaction_id;
                        $payment->invoice_id = $request->invoice_id;
                        $payment->description = "CAAP : ". $invoice_code_examen->order->code .". Frais : ".$request->get('fee');
                        $payment->save();
                    
                        DB::commit();
                    
                        return response()->json(['message' => 'INITIATED'], 200);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return response()->json(['error' => 'FAILED'], 500);
                    }
                }

            } catch (Exception $e) {
                // Gérer les erreurs de requête
                 $res_pay = $e->getMessage();
        return response()->json(['error' => 'FAILEDT', 'details' => $res_pay], 500);
            }

        }
    



    public function checkPaymentStatus(Request $request) {

        $payment = Payment::where('invoice_id', intval($request->invoice_id))->first();
        
        if($payment)
        {
    
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjZjYjU5YjU0LTIzMjAtNDM4Ny1hYzA2LTViNDUxN2JmN2Y0NyIsImlhdCI6MTcxNTg2MDEzM30.T4XlIq7YXnM06hjYIqM_5ASXKMTJv5Ore8aCWYVt-qE'
        ];

          // Verification status de la transaction
          $url = "https://pay.sckaler.cloud/api/transaction/status/{$payment->payment_id}";
                            
          try {
              $response = $client->request('GET', $url, [
                  'headers' => $headers
              ]);
  
              $responseBody = $response->getBody()->getContents();
              $response_transaction = json_decode($responseBody, true);
              $status = $response_transaction['status'];

                $payment->payment_status = $status;
                $payment->save();
                
                return response()->json(['message' => $status], 200);   
            } catch (Exception $e) {
              // Gestion des erreurs
              $responseBody = $e->getMessage();
              $response_transaction = json_decode($responseBody, true);
              $status = $response_transaction['status'];

              return response()->json(['message' => $status], 203);
            }


          return response()->json(["message" => "PaymentId existe"], 200);
        }else{
            return response()->json(['message' => "PaymentId n'et pas"], 500);
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     // Code pour connaitrele moyen de paiement utilise affin d'initier le paiement par MTN MOBILE MONEY
    //     $res_pay = "Test" ;
    //     $msg = 'N/A';
    //     $status = 'N/A';
    //     $transaction_id = 'N/A';
    //     $response_transaction = '';
        
    //     // Paiement MTN MOBILE MONEY
    //     if($request->payment_method == "MTN"){
    //         $client = new Client();

    //         $headers = [
    //             'Content-Type' => 'application/json',
    //             'Accept' => 'application/json',
    //             'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjZjYjU5YjU0LTIzMjAtNDM4Ny1hYzA2LTViNDUxN2JmN2Y0NyIsImlhdCI6MTcxNTg2MDEzM30.T4XlIq7YXnM06hjYIqM_5ASXKMTJv5Ore8aCWYVt-qE'
    //         ];

    //         // Assurez-vous que les valeurs récupérées sont correctes
    //         $numero_de_telephone = strval($request->numero_de_telephone);
    //         $amount_payer = strval($request->amount_payer);

    //         // Arrondir amount_payer et le convertir en entier
    //         $amount_payer = round(floatval($amount_payer));

    //         if (empty($numero_de_telephone) || empty($amount_payer)) {
    //             return response()->json(['error' => 'Les champs numero_de_telephone et amount_payer sont requis.'], 400);
    //         }

    //         $body = json_encode([
    //             "tel" => "229".$numero_de_telephone,
    //             "amount" =>  $amount_payer,
    //             "description" => "Description"
    //         ]);

    //         try {
    //             $guzzleRequest = new GuzzleRequest('POST', 'https://pay.sckaler.cloud/api/collection/mtn', $headers, $body);
    //             $response = $client->send($guzzleRequest);
    //             $res_pay = $response->getBody()->getContents();

    //             // recuperation des messages de reponses en attente 
    //             $res_pay_array = json_decode($res_pay, true);
    //             $msg = $res_pay_array['msg'] ?? 'N/A';
    //             $status = $res_pay_array['status'] ?? 'N/A';
    //             $transaction_id = $res_pay_array['transaction_id'] ?? 'N/A';

    //             // Paiement initier avec  succes
    //             if($status == "SUCCESS")
    //             {
    //                 $checkInvoiceId = Payment::where('invoice_id', $request->invoice_id)->first();

    //                 if($checkInvoiceId)
    //                 {
    //                     $payment = Payment::find($checkInvoiceId->id);
    //                     $payment->payment_name = $request->payment_method;
    //                     $payment->payment_status = "INITIATED";
    //                     $payment->payment_amount = $amount_payer;
    //                     $payment->payment_id = $transaction_id;
    //                     $payment->invoice_id = $request->invoice_id;
    //                     $payment->save();
    //                 }else{
    //                     $payment = new Payment();
    //                     $payment->payment_name = $request->payment_method;
    //                     $payment->payment_status = "INITIATED";
    //                     $payment->payment_amount = $amount_payer;
    //                     $payment->payment_id = $transaction_id;
    //                     $payment->invoice_id = $request->invoice_id;
    //                     $payment->save();
    //                 }

    //                 return response()->json(['message' => 'INITIATED']);
    //             }
    //         } catch (Exception $e) {
    //             // Gérer les erreurs de requête
    //             $res_pay = $e->getMessage();
    //         }

    //     }
    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
