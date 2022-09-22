<div style="display:inline-block; ">
    <span style="display: inline-block;padding-top: 5px; font-size:25px; margin-top:-50px "><img
            src="{{ public_path('adminassets/images/Labo_Logo_1.png') }}" alt=""></span>
    <div
        style="display: inline-block; padding: 5px; position: absolute; top:20px; right: 0px; padding: 10px; text-align:right;">
        <b><span style="font-size:17px; text-align:right;"> CENTRE ADECHINA ANATOMIE PATHOLOGIE</span></b>
        <br><span style="font-size:10px; text-align:right;">Laboratoire d’Anatomie Pathologique</span>
    </div>
</div>
<div style="display: inline-block; position: absolute; right: 0px;width: 150px;padding: 10px;  margin-top:-20px">
    <p>
        <b>N° ANAPTH :</b> XXXXXX
        <br>
        <b>Date :</b> 14 Juillet 2022
    </p>
</div>
<div
    style="margin-top:20px; background-color:#0070C1; width:100%; height:50px;color:rgb(255,255,255); text-align: center; padding-top:19px;font-size:25px;">
    <b>COMPTE RENDU HISTOPATHOLOGIE</b>
</div>
<br><br>
<div>
    <fieldset style="border: 3px solid rgb(0,0,0,0)">
        <legend
            style="font-size: 1.5em; padding-bottom: 10px; padding-left: 5px; padding-right: 5px; border:none; background-color:rgb(255,255,255); ">
            <b>Informations prélèvement</b>
        </legend>
        <p style="margin-left:10px; margin-right:10px; display:block; width: 100%;">


        <table style="max-width: 100%;width: 500px ">
            <tbody>
                <tr>
                    <th>Nom :</th>
                    <td>{{ $patient_firstname }} </td>
                    <th>Date prélèvement : </th>
                    <td>12 Juillet 2022 </td>
                </tr>
                <tr>
                    <th>Prénoms :</th>
                    <td>{{ $patient_lastname }} </td>
                    <th>Date d’arrivée labo : </th>
                    <td>13 Juillet 2022 </td>
                </tr>
                <tr>
                    <th>Age :</th>
                    <td>{{ $patient_age }} </td>
                    <th>Service demandeur </th>
                    <td>{{ $hospital_name }} </td>
                </tr>
                <tr>
                    <th>Sexe :</th>
                    <td>{{ $patient_genre }} </td>
                    <th>Médecin prescripteur : </th>
                    <td> {{ $doctor_name }} </td>
                </tr>
                <tr>
                    <td colspan=4></td>
                </tr>
                <tr>
                    <td colspan=4></td>
                </tr>
                <tr>
                    <td colspan=4></td>
                </tr>
                <tr>
                    <td colspan=4></td>
                </tr>
                <tr>
                    <th>Infos complémentaire *: </th>
                    <td>it maecenas tortor. Donec.</td>

                    <th>Commentaire *: </th>
                    <td>it maecenas tortor. Donec </td>
                </tr>
            </tbody>
        </table>
        </p>
        <br>
    </fieldset>
</div>
<br><br>
<div>
    <fieldset style="border: 3px solid rgb(0,0,0,0)">
        <legend
            style="font-size: 1.5em;padding-bottom: 10px; padding-left: 5px; padding-right: 5px;border:none; background-color:rgb(255,255,255);">
            <b>Récapitulatifs </b>
        </legend>
        <p
            style="margin-left:10px; margin-right:10px; display: inline-block; width: 75%;font-family:Courier; font-size:14px; ">
            <b>Examen / Type </b> : Cytoponction Mammaire
        <p style="text-justify; width: -moz-fit-content; width: fit-content;">
            {!! $content !!}
        </p>
        </p>
        <br>
    </fieldset>
</div><br><br>
<div style="">
    <table style="width: 100%;">
        <tr>
            <td style="text-align: left;    width: 25%"></td>
            <td style="text-align: left;    width: 25%"></td>
            <td style="text-align: right;    width: 25%"><img width="50" height="60"
                    src="{{ storage_path('app/public/' . $signature1) }}" alt=""><br> <i
                    style="font-size:15px; color: blue"><br>{{ $signatory1 }}
                    <br></i></td>
            <td style="text-align: right;    width: 25%"><img width="50" height="60"
                    src="{{ storage_path('app/public/' . $signature2) }}" alt=""><br> <i
                    style="font-size:15px; color: blue margin-top: 100px"><br>{{ $signatory2 }}
                </i></td>
        </tr>
    </table>

</div>
<br><br>

<page_footer>
    <table style="width: 100%;">
        <tr>
            <td style="text-align: left;    width: 100%"> Centre ADECHINA Anatomie Pathologique • <br>
                Adresse : XXXXXXXXXXXXXX • Téléphone : XXXXXXXX/XXXXXXXX • RCCM XXXXXXXXXXXXXXXXXXXXXXXXXXXX
                <br>
                Contact@caap.bj • Ouvert du Lundi au Vendredi de 08:00 - 12:00 / 14:00 - 18:00 • www.caap.bj
            </td>
        </tr>
    </table>
</page_footer>
