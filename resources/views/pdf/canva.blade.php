<?php
setlocale(LC_TIME, 'fr_FR');
date_default_timezone_set('Europe/Paris');
?>


<style>
    .border_b {
        border-bottom: 2px solid rgb(0, 0, 0, 0);
        border-radius: 5px;
    }

    .border_t {
        border-top: 2px solid rgb(0, 0, 0, 0);
        border-radius: 5px;
    }
</style>

<page backbottom="10mm">

    <div style="display:inline-block; ">
        <span style="display: inline-block;padding-top: 5px; font-size:25px; margin-top:-50px "><img
                src="{{ public_path('adminassets/images/Logo_long_CAAP@4x.png') }}" width="200px;" alt=""></span>
        <div
            style="display: inline-block; padding: 5px; position: absolute; top:20px; right: 0px; padding: 10px; text-align:right;">
            <b><span style="font-size:17px; text-align:right;"> CENTRE ADECHINA ANATOMIE PATHOLOGIQUE</span></b>
            <br><span style="font-size:10px; text-align:right;">Laboratoire d’Anatomie Pathologique</span>
        </div>
    </div>
    <div
        style="display: inline-block; position: absolute; top: 0; left: 0; padding: 10px; text-align: right;">
        <p>
            <b>N° ANAPTH :</b> {{ $code }}
            <b>{{ $test_affiliate != null ? '| Examen reference : ' : '' }}</b>
            {{ $test_affiliate != null ? $test_affiliate : '' }}
            <br>
            <b>Date :</b> {{ $current_date }}
        </p>
    </div>

    <div style="display: inline-block; position: absolute; top: 0; right: 0; width: 50px; padding: 10px; text-align: right;">
        <img src="{{ asset('storage/settings/app/' . $code .'_qrcode.png') }}"  style="width: 65px; alt="" srcset="">
    </div>

    <div
        style="margin-top:20px; background-color:#0070C1; width:100%; height:50px;color:rgb(255,255,255); text-align: center; padding-top:19px;font-size:25px; text-transform: uppercase;">
        <b> {{ $title }} </b>
    </div>
    <br><br>
    <div>
        <h3
            style="padding-left: 5px; padding-right: 5px; border:none; background-color:rgb(255,255,255); text-transform: uppercase; color:#0070C1; ">
            <b>Informations prélèvement</b>
        </h3>

        <p style="margin-left:10px; margin-right:10px; display:block; width: 100%;">

        <table style="max-width: 100%;width: 500px ">
            <tbody>
                <tr>
                    <th width="30%">Nom :</th>
                    <td width="50%;">{{ $patient_firstname }} </td>
                    <th width="30%;">Date prélèvement : </th>
                    <td width="50%;">{{ $prelevement_date }} </td>
                </tr>

                <tr>
                    <th>Prénoms :</th>
                    <td>{{ $patient_lastname }} </td>
                    <th>Date d’arrivée labo : </th>
                    <td> {{ $created_at }} </td>
                </tr>

                <tr>
                    <th>Age :</th>
                    <td>{{ $patient_age }} {{ $patient_year_or_month }} </td>
                    <th>Service demandeur :</th>
                    <td>{{ $hospital_name }} </td>
                </tr>

                <tr>
                    <th>Sexe :</th>
                    <td>{{ $patient_genre }} </td>
                    <th>Médecin prescripteur : </th>
                    <td> {{ $doctor_name }} </td>
                </tr>
            </tbody>
        </table>

        </p>
        <br>
    </div>

    <h3
        style="padding-left: 5px; padding-right: 5px; border:none; background-color:rgb(255,255,255); text-transform: uppercase; color:#0070C1; ">

    </h3>
    {{-- <br> --}}
    {{-- <div class="border_t"></div> --}}
    {!! $content !!}

    {{-- <br><br><br> --}}

    {{-- <div class="border_b"></div> --}}

    @if ($content_supplementaire != '')
        <h3
            style="padding-left: 5px; padding-right: 5px; border:none; background-color:rgb(255,255,255); text-transform: uppercase; color:#0070C1; ">

        </h3>
        {!! $content_supplementaire !!}
    @endif

    <div style="">
        <table style="width: 100%;">
            <tr>

                <td style="text-align: center;    width: 25%">
                    @if ($signatory1 != null)
                        @if ($signature1 != null)
                            <img width="85" src="{{ asset('storage/' . $signature1) }}" alt="">
                        @endif
                        <br><br>{{ $signatory1 }}
                    @endif
                </td>

                <td style="text-align: center;    width: 25%">
                    @if ($signatory2 != null)
                        @if ($signature2 != null)
                            <img width="85" src="{{ asset('storage/' . $signature2) }}" alt="">
                        @endif
                        <br><br>{{ $signatory2 }}
                    @endif

                </td>

                <td style="text-align: center;    width: 25%">
                    @if ($signatory3 != null)
                        @if ($signature3 != null)
                            <img width="85" src="{{ asset('storage/' . $signature3) }}" alt="">
                        @endif
                        <br><br>{{ $signatory3 }}
                    @endif
                </td>
            </tr>
        </table>
        <!--@if ($test_affiliate != '')
Examen Référence: {{ $test_affiliate }}
@endif-->
    </div>
    <br><br>
    <page_footer>
        <table style="width: 100%; margin-top:1em !important">
            <tr>
                <td style="text-align: left; width: 100%; font-size:12px;">
                    {{ $footer }}
                </td>
            </tr>
        </table>
    </page_footer>
</page>
