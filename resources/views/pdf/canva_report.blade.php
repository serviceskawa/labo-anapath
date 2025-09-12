<?php
setlocale(LC_TIME, 'fr_FR');
date_default_timezone_set('Europe/Paris');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $data['code'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body>
    <div backbottom="10mm" style="margin: -15px;">
        <div style="display:inline-block;">
            <span style="display: inline-block;">
                <img src="{{ $data['entete'] }}" width="100%;" alt=""></span>
        </div>

        <div class="row" style="margin-top: -2px;">
            <div style="display: inline-block; position: absolute; text-align: left; margin-left:10px;">
                <p style="font-size: 13px;">
                    <b>N° ANAPTH :</b> {{ $data['test_order_code'] }}
                    <b>{{ $data['test_affiliate'] != null ? '| Examen reference : ' : '' }}</b>
                    {{ $data['test_affiliate'] != null ? $data['test_affiliate'] : '' }}
                    <br>
                    <b>Date validation:</b> {{ $data['signature_date'] }}
                    <br>
                    <b>Date impression:</b> {{ $data['current_date'] }}
                </p>
            </div>

            <div
                style="display: inline-block; position: absolute; margin-top:0px; right: 0; width: 50px; padding: 0px; text-align: right;">
                <img src="{{ storage_path('app/public/settings/app/' . $data['code'] . '_qrcode.png') }}"
                    style="width: 65px;" alt="" srcset="">
            </div>
        </div>

        <div
            style="background-color:#0070C1; width:96%; padding:15px; color:white; text-align:center;font-size:16px; text-transform: uppercase; margin-top:85px;">
            <b> {{ $data['title'] }} </b>
        </div>

        <div style="margin-top: 10px;">
            <span
                style="padding-left: 10px; padding-right: 5px; margin-top:15px; border:none; background-color:rgb(255,255,255); font-size:16px; text-transform: uppercase;">
                <b>Informations prélèvement</b>
            </span>

            <table style="width: 100%; margin-left : 15px; font-size:13px; margin-top:5px;">
                <tbody>
                    <tr>
                        <th width="50%;text-align : left;">Nom :</th>
                        <td width="50%;">{{ $data['patient_firstname'] }}</td>
                        <th width="50%;text-align : left;">Date prélèvement : </th>
                        <td width="50%;">{{ $data['prelevement_date'] }} </td>
                    </tr>

                    <tr>
                        <th width="50%; text-align:left;">Prénoms :</th>
                        <td width="50%">{{ $data['patient_lastname'] }}</td>
                        <th width="50%; text-align:left;">Date d’arrivée labo : </th>
                        <td width="50%"> {{ $data['created_at'] }} </td>
                    </tr>

                    <tr>
                        <th width="50%; text-align:left;">Age :</th>
                        <td width="50%">{{ $data['patient_age'] }} {{ $data['patient_year_or_month'] }} </td>
                        <th width="50%; text-align:left;">Service demandeur :</th>
                        <td width="50%">{{ $data['hospital_name'] }} </td>
                    </tr>

                    <tr>
                        <th width="50%; text-align:left;">Sexe :</th>
                        <td width="50%">{{ $data['patient_genre'] }} </td>
                        <th width="50%; text-align:left;">Médecin prescripteur : </th>
                        <td width="50%"> {{ $data['doctor_name'] }} </td>
                    </tr>
                </tbody>
            </table>
            </p>
        </div>


        <hr style="margin-top:15px; color:#0070C1;">

        <span style="font-size: 13px;">{!! $data['content'] !!}</span>
        <span style="font-size: 13px;">{!! $data['content_micro'] !!}</span>

        @if ($data['content_supplementaire'] != '')
            <span style="font-size: 13px;">{!! $data['content_supplementaire'] !!}</span>
            <span style="font-size: 13px;">{!! $data['content_supplementaire_micro'] !!}</span>
        @endif

        <div style="margin-top:30px;">
            <table style="width: 100%;">
                @php
                    $show_signator_invoice = App\Models\SettingApp::where('key', 'show_signator_invoice')->first()
                        ->value;
                @endphp
                @if ($data['status'] == 1)
                    <tr>
                        <td style="text-align: left; width: 35%; vertical-align: bottom;">
                            @if ($show_signator_invoice == 'OUI' && $data['signature1'])
                                <img width="85"
                                    src="{{ public_path('adminassets/images/' . $data['signature1']) }}"
                                    alt="">
                                <br><br>
                                <span style="font-size: 13px;">{{ $data['signator'] }}</span>
                            @endif
                        </td>

                        <td style="text-align: left; width: 35%; vertical-align: bottom;">
                            @if ($data['revew_by'] != null)
                                <span style="font-size: 13px;">{{ $data['report_review_title'] }}</span>
                                <br><strong>
                                    <span style="font-size: 13px;">{{ $data['revew_by'] }}</span>
                                </strong>
                            @endif
                        </td>
                    </tr>
                @endif
            </table>
        </div>
        <br><br>

        <page_footer style="position: fixed; bottom: 0; left: 0; width: 100%;">
            <table style="width: 100%; margin-top:1em !important">
                <tr>
                    <td style="text-align: left; width: 100%; font-size:12px;">
                        {{ $data['footer'] }}
                    </td>
                </tr>
            </table>
        </page_footer>
    </div>

</body>

</html>
