<?php

namespace App\Http\Controllers;

use App\Models\AppelTestOder;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AppelTestOderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = new Client();


        // Pour lancer un appel
        $responsevocal = $client->request('POST', 'https://gestion.caap.bj/api/testOrder/webhook', [
            'headers' => [
                'Authorization' => '',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                    "type"=> "voice",
                    "account_id"=> "12ert-1244-iuoi",
                    "voice_id"=> "78yuio-uy7865-987uytr",
                    "event"=> "voice.answered"
            ],
        ]);

        $vocal = json_decode($responsevocal->getBody(), true);
        $appelTestOder = AppelTestOder::create($vocal);

        dd($appelTestOder);

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AppelTestOder  $appelTestOder
     * @return \Illuminate\Http\Response
     */
    public function show(AppelTestOder $appelTestOder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AppelTestOder  $appelTestOder
     * @return \Illuminate\Http\Response
     */
    public function edit(AppelTestOder $appelTestOder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AppelTestOder  $appelTestOder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AppelTestOder $appelTestOder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AppelTestOder  $appelTestOder
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppelTestOder $appelTestOder)
    {
        //
    }
}
