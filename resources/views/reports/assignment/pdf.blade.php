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
                src="{{ Storage::url($prefixe_code_demande_examen) }}" width="200px;" alt=""></span>
    </div>

    <div
        style="margin-top:20px; background-color:#0070C1; width:100%; height:50px;color:rgb(255,255,255); text-align: center; padding-top:19px;font-size:25px; text-transform: uppercase;">
        <b> Affectation de compte rendu </b>
    </div>
    <br><br>
    <div>
        <h3
            style="border:none; background-color:rgb(255,255,255); text-transform: uppercase; color:#0070C1; ">
            <b>Docteur : {{ $doctor }}</b>
        </h3>

        <h3
            style="border:none; background-color:rgb(255,255,255); text-transform: uppercase; color:#0070C1; ">
            <b>Date : {{ $date }}</b>
        </h3>

        <p style="margin-left:10px; margin-right:10px; display:block; width: 100%;">
        <h4
            style="padding-left: 5px; padding-right: 5px; border:none; background-color:rgb(255,255,255); text-transform: uppercase; color:#0070C1; ">
            <b>Liste des comptes rendu</b>
        </h4>

        <table style="max-width: 100%;width: 500px">
            <thead style="height:25px">
                <tr>
                    <th>Compte rendu</th>
                    <th>Commentaire</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assignments as $assignment)
                    <tr style="">
                        <td style="width: 100%; height:25px">
                            {{ $assignment->report->code }} ( {{ $assignment->report->order->code }})
                        </td>
                        <td style="width: 100%; height:25px">
                            {{ $assignment->comment }}
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        </p>
        <br>
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
