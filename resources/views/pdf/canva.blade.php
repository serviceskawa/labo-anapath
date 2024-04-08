<?php
// setlocale(LC_TIME, 'fr_FR');
// date_default_timezone_set('Europe/Paris');
?>

{{-- 


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

    <div style="display:inline-block; margin:0; padding:0;">
        <span style="display: inline-block;"><img src="{{ public_path('adminassets/images/entete_pdf_cr.png') }}"
                width="100%;" alt=""></span>
    </div>

    <div style="display: inline-block; margin-top:150px; position: absolute; left: 0; padding: 10px; text-align: left;">
        <p>
            <b>N° ANAPTH :</b> {{ $test_order_code }}
            <b>{{ $test_affiliate != null ? '| Examen reference : ' : '' }}</b>
            {{ $test_affiliate != null ? $test_affiliate : '' }}
            <br>
            <b>Date validation:</b> {{ $signature_date }}
            <br>
            <b>Date impression:</b> {{ $current_date }}
        </p>
    </div>

    <div
        style="display: inline-block; position: absolute; margin-top: 150px; right: 0; width: 50px; padding: 10px; text-align: right;">
        <img src="{{ asset('storage/settings/app/' . $code .'_qrcode.png') }}" style="width: 65px;" alt="" srcset="">
    </div>

    <div
        style=" margin-top:90px; background-color:#1690F5; width:100%; padding:15px; color:white; text-align:
            center;font-size:16px; text-transform: uppercase;">
        <b> {{ $title }} </b>
    </div>
    <div>
        <span
            style="padding-left: 10px; padding-right: 5px; margin-top:15px; border:none; background-color:rgb(255,255,255); font-size:16px; text-transform: uppercase; ">
            <b>Informations prélèvement</b>
        </span>

        <p style="margin-left:10px; margin-right:10px; display:block; width: 100%;">

        <table style="max-width: 100%;width: 500px; margin-top:5px;">
            <tbody>
                <tr>
                    <th width="30%">Nom :</th>
                    <td width="50%;">{{ $patient_lastname }}</td>
                    <th width="30%;">Date prélèvement : </th>
                    <td width="50%;">{{ $prelevement_date }} </td>
                </tr>

                <tr>
                    <th>Prénoms :</th>
                    <td>{{ $patient_firstname }}</td>
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
    </div>
    <hr style="height:1px; margin-top:5px;">


    {!! $content !!}
    {!! $content_micro !!}

    @if ($content_supplementaire != '')
    {!! $content_supplementaire !!}
    {!! $content_supplementaire_micro !!}
    @endif

    <div style="margin-top:30px;">
        <table style="width: 100%;">

            // si le rapport est validé 
            @if ($status == 1)
            <tr>
                <td style="text-align: left; width: 33%; vertical-align: bottom;">
                    @if ($signator && $signature1)
                        <img width="85" src="{{ asset('adminassets/images/'.$signature1) }}" alt="">
                        <br><br>{{ $signator }}
                    @elseif ($signator && !$signature1)
                        {{ $signator }}
                    @else
                        <span style="color: red;">Aucun signataire spécifié</span>
                    @endif
                </td>

                <td style="text-align: right; width: 34%; vertical-align: bottom;">
                    @if ($revew_by != null)
                    {{ $report_review_title }}
                    <br><strong>{{ $revew_by }}</strong>
                    @endif
                </td>
            </tr>
            @endif


        </table>
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
</page> --}}


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

    <div style="display:inline-block; margin:0; padding:0;">
        <span style="display: inline-block;"><img src="{{ public_path('adminassets/images/entete_pdf_cr.png') }}"
                width="100%;" alt=""></span>
    </div>

    <div style="display: inline-block; margin-top:150px; position: absolute; left: 0; padding: 10px; text-align: left;">
        <p>
            <b>N° ANAPTH :</b> {{ $test_order_code }}
            <b>{{ $test_affiliate != null ? '| Examen reference : ' : '' }}</b>
            {{ $test_affiliate != null ? $test_affiliate : '' }}
            <br>
            <b>Date validation:</b> {{ $signature_date }}
            <br>
            <b>Date impression:</b> {{ $current_date }}
        </p>
    </div>

    <div
        style="display: inline-block; position: absolute; margin-top: 150px; right: 0; width: 50px; padding: 10px; text-align: right;">
        <img src="{{ asset('storage/settings/app/' . $code .'_qrcode.png') }}" style="width: 65px;" alt="" srcset="">
    </div>

    <div
        style=" margin-top:90px; background-color:#1690F5; width:100%; padding:15px; color:white; text-align:
            center;font-size:16px; text-transform: uppercase;">
        <b> {{ $title }} </b>
    </div>
    <div>
        <span
            style="padding-left: 10px; padding-right: 5px; margin-top:15px; border:none; background-color:rgb(255,255,255); font-size:16px; text-transform: uppercase; ">
            <b>Informations prélèvement</b>
        </span>

        <p style="margin-left:10px; margin-right:10px; display:block; width: 100%;">

        <table style="max-width: 100%;width: 500px; margin-top:5px;">
            <tbody>
                <tr>
                    <th width="30%">Nom :</th>
                    <td width="50%;">{{ $patient_lastname }}</td>
                    <th width="30%;">Date prélèvement : </th>
                    <td width="50%;">{{ $prelevement_date }} </td>
                </tr>

                <tr>
                    <th>Prénoms :</th>
                    <td>{{ $patient_firstname }}</td>
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
    </div>
    <hr style="height:1px; margin-top:5px;">


    {!! $content !!}
    {!! $content_micro !!}

    @if ($content_supplementaire != '')
    {!! $content_supplementaire !!}
    {!! $content_supplementaire_micro !!}
    @endif

    <div style="margin-top:30px;">
        <table style="width: 100%;">

           
            @if ($status == 1)
            <tr>
                <td style="text-align: left; width: 33%; vertical-align: bottom;">
                    @if ($signator && $signature1)
                        <img width="85" src="{{ asset('adminassets/images/'.$signature1) }}" alt="">
                        <br><br>{{ $signator }}
                    @elseif ($signator && !$signature1)
                        {{ $signator }}
                    @else
                        <span style="color: red;">Aucun signataire spécifié</span>
                    @endif
                </td>

                <td style="text-align: right; width: 34%; vertical-align: bottom;">
                    @if ($revew_by != null)
                    {{ $report_review_title }}
                    <br><strong>{{ $revew_by }}</strong>
                    @endif
                </td>
            </tr>
            @endif


        </table>
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