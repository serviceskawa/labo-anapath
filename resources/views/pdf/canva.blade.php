<?php
setlocale(LC_TIME, 'fr_FR');
date_default_timezone_set('Europe/Paris');

?>

<div style="display:inline-block; ">
    <span style="display: inline-block;padding-top: 5px; font-size:25px; margin-top:-50px "><img
            src="{{ public_path('adminassets/images/Logo_long_CAAP@4x.png') }}" width="200px;" alt=""></span>
    <div
        style="display: inline-block; padding: 5px; position: absolute; top:20px; right: 0px; padding: 10px; text-align:right;">
        <b><span style="font-size:17px; text-align:right;"> CENTRE ADECHINA ANATOMIE PATHOLOGIQUE</span></b>
        <br><span style="font-size:10px; text-align:right;">Laboratoire d’Anatomie Pathologique</span>
    </div>
</div>
<div style="display: inline-block; position: absolute; right: 0px;width: 200px;padding: 10px;  margin-top:-20px">
    <p>
        <b>N° ANAPTH :</b> {{$code}}
        <br>
        <b>Date :</b> {{$current_date}}
    </p>
</div>
<div
    style="margin-top:20px; background-color:#0070C1; width:100%; height:50px;color:rgb(255,255,255); text-align: center; padding-top:19px;font-size:25px;">
    <b>COMPTE RENDU HISTOPATHOLOGIQUE</b>
</div>
<br><br>
<div>
    <fieldset style="border: solid rgb(0,0,0,0)">
        <legend
            style="font-size: 1.5em; padding-bottom: 10px; padding-left: 5px; padding-right: 5px; border:none; background-color:rgb(255,255,255); ">
            <b>Informations prélèvement</b>
        </legend>
        <p style="margin-left:10px; margin-right:10px; display:block; width: 100%;">


        <table style="max-width: 100%;width: 500px ">
            <tbody>
                <tr>
                    <th width="25%;">Nom :</th>
                    <td width="25%;">{{ $patient_firstname }} </td>
                    <th width="40%;">Date prélèvement : </th>
                    <td width="20%;">{{$prelevement_date}} </td>
                </tr>
                <tr>
                    <th>Prénoms :</th>
                    <td>{{ $patient_lastname }} </td>
                    <th>Date d’arrivée labo : </th>
                    <td> {{ $created_at }} </td>
                </tr>
                <tr>
                    <th>Age :</th>
                    <td>{{ $patient_age }} </td>
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
    </fieldset>
</div>
<br><br>
<div>
    <fieldset style="border: solid rgb(0,0,0,0)">

        <legend
            style="font-size: 1.5em; padding-left: 5px; padding-right: 5px; border:none; background-color:rgb(255,255,255);">
            <b>Récapitulatifs</b>
        </legend>

        <p
            style="text-left; width: -moz-fit-content; width: fit-content; margin-left:10px; margin-right:10px; font-familly:Montserrat;">

        <table style="table-layout: fixed;max-width: 100%;width: 500px ">
            <tbody>
                <tr>
                    <td> {!! $content !!} </td>
                </tr>
            </tbody>
        </table>
        </p>

        <br>
    </fieldset>

</div><br><br>
<div style="">
    <table style="width: 100%;">
        <tr>
            <!-- <td style="text-align: left;    width: 10%"></td> -->

            <td style="text-align: center;    width: 33%">
                @if ($signatory1 != null)
                <img width="100" src="{{ storage_path('app/public/' . $signature1) }}" alt=""><br><br>{{
                $signatory1 }}
                @endif
            </td>
            <td style="text-align: center;    width: 33%">
                @if ($signatory2 != null)
                <img width="200" src="{{ storage_path('app/public/' . $signature2) }}" alt=""><br><br>{{
                $signatory2 }}
                @endif

            </td>
            <td style="text-align: center;    width: 33%">
                @if ($signatory3 != null)
                <img width="85" src="{{ storage_path('app/public/' . $signature3) }}" alt=""><br><br>{{
                $signatory3 }}
                @endif
            </td>
        </tr>
    </table>

</div>
<br><br>

<page_footer>
    <table style="width: 100%;">
        <tr>
            <td style="text-align: left; width: 100%; font-size:12px;"> Centre ADECHINA Anatomie Pathologique • <br>
                Adresse : Carre 1915 "G" Fifadji, 072 BP 059 Cotonou, Benin • Téléphone : (+229)96110311 • RCCM
                RB/COT/18 B22364<br>
                Contact@caap.bj • Ouvert du Lundi au Vendredi de 08:00 - 17:00 • www.caap.bj
            </td>
        </tr>
    </table>
</page_footer>