@extends('layouts.app2')

@section('title', 'Factures')
@section('content')
    <style>
        .payerButton {
            visibility: hidden;
            /* Applique la visibilité cachée */
        }
    </style>

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ $invoice->status_invoice != 1 ? 'Reçu de paiement' : 'Facture d\'avoir' }}
                    {{ $invoice->order ? 'de ' . $invoice->order->code : '' }} : <a
                        href="{{ route('invoice.pdf', $invoice->id) }}" target="_blank" class="btn btn-info btn-sm">Voir</a>
                </h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!-- Invoice Logo-->
                    <div class="clearfix">
                        <div class="float-start mb-3">
                            <img src="{{ $setting ? Storage::url($setting->logo) : '' }}" alt="" height="75">
                        </div>
                    </div>

                    <div class="row">
                        <hr>
                    </div>

                    <!-- Invoice Detail-->
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="float-sm-start">
                                <p><strong>
                                        {{ $invoice->status_invoice != 1
                                            ? 'Facture de vente'
                                            : 'Facture
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    d\'avoir' }}
                                    </strong></p>

                                <p class="font-15"><strong>Date: </strong> {{ $invoice->created_at }}</p>

                                <p class="font-15"><strong>Code: </strong> <span
                                        class="">{{ $invoice->status_invoice != 1 ? $invoice->code : $refund->code }}</span>
                                    @if ($invoice->paid == 1)
                                        <span style="text-transform: uppercase; font-weight: bold;">[Payé]</span>
                                    @else
                                        <span style="text-transform: uppercase; font-weight: bold;">[En attente]</span>
                                    @endif
                                </p>

                                @if ($invoice->status_invoice != 1)
                                    <p class="font-15"><strong>Contrat: </strong> <span
                                            class="">{{ $invoice->contrat
                                                ? $invoice->contrat->name
                                                : ($invoice->order
                                                    ? ($invoice->order->contrat
                                                        ? $invoice->order->contrat->name
                                                        : '')
                                                    : '') }}</span>
                                    </p>
                                @else
                                    <p class="font-15"><strong>Référence: </strong> <span
                                            class="">{{ $refund->invoice ? $refund->invoice->code : '' }}</span>
                                    </p>
                                @endif
                                <p class="font-15"><strong>CODE MECeF / DGI: </strong> <span class=""
                                        style="text-transform: uppercase;">&nbsp;{{ $invoice->code_normalise ?? '' }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="float-sm-start">
                                <p>
                                    <strong> Adressée à:
                                    </strong>
                                </p>

                                <p class="font-15"><strong>Nom: </strong> {{ $invoice->client_name }}</p>

                                <p class="font-15"><strong>Adresse: </strong> <span
                                        class="">{{ $invoice->patient ? $invoice->patient->firstname . ' ' . $invoice->patient->lastname : $invoice->client_address }}</span>
                                </p>

                                <p class="font-15"><strong>Code client: </strong> <span class=""
                                        style="text-transform:uppercase;">{{ $invoice->patient ? $invoice->patient->code : '' }}</span>
                                </p>

                                <p class="font-15"><strong>Contact client: </strong> <span
                                        class="">{{ $invoice->telephone1 ? $invoice->telephone1 . ' ' . $invoice->telephone2 : ' ' }}</span>
                                </p>

                                <p class="font-15"><strong>Demande d'examen: </strong> <span
                                        class="font-weight:bold;">{{ $invoice->order ? remove_hyphen($invoice->order->code) : '' }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="float-sm-end">
                                @if ($invoice->code_normalise != '')
                                    <img src="data:image/png;base64,{{ $qrCodeBase }}" alt="QR Code" width="150"
                                        height="150">
                                @endif
                            </div>
                        </div>

                    </div>

                    @php
                        $total_a_payer = ajouterPourcentage($invoice->total);
                        $invoiceVerify = App\Models\Payment::where('invoice_id', $invoice->id)->first();
                        $invoiceMethodPayment = $invoiceVerify ? $invoiceVerify->payment_name : '';
                    @endphp
                    <div class="row">
                        <div class="col-12">
                            <div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mt-4">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Désignation</th>
                                            <th>Quantité</th>
                                            <th>Prix</th>
                                            <th>Remise</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($refund)
                                            <tr>
                                                <td>1</td>
                                                <td>{{ $refund ? ($refund->reason ? $refund->reason->description : '') : '' }}
                                                </td>
                                                <td>1</td>
                                                <td>{{ $refund ? $refund->montant : '' }}</td>
                                                <td>0.0 </td>
                                                <td class="text-end">{{ $refund ? $refund->montant : '' }}</td>
                                            </tr>
                                        @else
                                            @foreach ($invoice->details as $key => $item)
                                                <tr>
                                                    <td>{{ $key + 1 }} </td>
                                                    <td>
                                                        <b>{{ $item->test_name }}</b>
                                                    </td>
                                                    <td>1</td>
                                                    <td>{{ $item->price }}</td>
                                                    <td>{{ $item->discount }}</td>
                                                    <td class="text-end">{{ $item->total }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">

                        </div>
                        <div class="col-sm-6">
                            <div class="float-end mt-3 mt-sm-0">
                                <p><b>Sous-total : </b>
                                    <span
                                        class="float-end">{{ number_format(abs($invoice->subtotal), 0, ',', ' ') }}</span>
                                </p>
                                <p><b>Montant TTC : </b>{{ number_format(abs($invoice->total), 0, ',', ' ') }} FCFA</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>


                    @if ($invoice->contrat)
                        @if ($invoice->contrat->is_close == 1)

                            @if (getOnlineUser()->can('view-cashier'))
                                @if ($settingInvoice != null && $invoice->paid != 1)
                                    <div>
                                        <div class="row d-flex align-items-end">
                                            <div class="col-md-4 col-12">

                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label">Type de
                                                        paiement</label>
                                                    <select class="form-select select2" data-toggle="select2" name="payment"
                                                        value="{{ $invoice->payment }}" id="payment" required>
                                                        <option {{ $invoice->payment == 'ESPECES' ? 'selected' : '' }}
                                                            value="ESPECES">
                                                            ESPECES</option>
                                                        <option {{ $invoice->payment == 'MOBILEMONEY' ? 'selected' : '' }}
                                                            value="MOBILEMONEY">MOBILE MONEY</option>

                                                        <option {{ $invoice->payment == 'CHEQUES' ? 'selected' : '' }}
                                                            value="CHEQUES">
                                                            CHEQUES</option>

                                                        <option {{ $invoice->payment == 'VIREMENT' ? 'selected' : '' }}
                                                            value="VIREMENT">
                                                            VIREMENT</option>


                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Code de la facture normalisée</label>

                                                    <input type="text" name="code" placeholder="Code MECeF/DGI"
                                                        minlength="24" maxlength="24" id="code" class="form-control">
                                                </div>
                                            </div>

                                            <!-- Verification caisse ferme ou non -->
                                            <div class="col-md-4 col-12">
                                                @if ($cashbox->statut == 0)
                                                    <div class="alert alert-warning alert-dismissible fade show"
                                                        role="alert">
                                                        <strong>Caisse fermée - </strong> Veuillez ouvrir la caisse avant de
                                                        procéder à
                                                        l'encaissement.
                                                    </div>
                                                @else
                                                    @if (getOnlineUser()->can('view-cashier'))
                                                        <button type="button" onclick="updateStatus()"
                                                            class="btn btn-success mb-3"><i class="mdi mdi-cash"></i>
                                                            Terminer la facture</button>
                                                    @endif

                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                        @endif
                    @else
                        @if (getOnlineUser()->can('view-cashier'))
                            @if ($settingInvoice != null && $invoice->paid != 1)
                                <div>
                                    <div class="row d-flex align-items-end">
                                        @if ($invoiceVerify == null)
                                            <div class="col-md-4 col-12">
                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label">Type de
                                                        paiement</label>
                                                    <select class="form-select select2" data-toggle="select2"
                                                        name="payment" value="{{ $invoice->payment }}" id="payment"
                                                        required>
                                                        <option {{ $invoice->payment == 'ESPECES' ? 'selected' : '' }}
                                                            value="ESPECES">
                                                            ESPECES</option>

                                                        <option value="MOBILEMONEY-MTN">MOBILE MONEY - MTN</option>

                                                        <option value="MOBILEMONEY-MOOV">MOBILE MONEY - MOOV</option>

                                                        <option value="MOBILEMONEY">MOBILE MONEY</option>

                                                        <option value="CHEQUES">CHEQUES</option>

                                                        <option value="VIREMENT">VIREMENT</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-4 col-12">
                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label">Type de
                                                        paiement</label>
                                                    <select class="form-select select2" data-toggle="select2"
                                                        name="payment" disabled value="{{ $invoice->payment }}"
                                                        id="payment" required>

                                                        <option
                                                            {{ $invoiceMethodPayment == 'MOBILEMONEY-MTN' ? 'selected' : '' }}
                                                            value="MOBILEMONEY-MTN">MOBILE MONEY - MTN</option>



                                                        <option
                                                            {{ $invoiceMethodPayment == 'MOBILEMONEY-MOOV' ? 'selected' : '' }}
                                                            value="MOBILEMONEY-MOOV">MOBILE MONEY - MOOV</option>

                                                    </select>
                                                </div>
                                            </div>
                                        @endif


                                        <div class="col-md-4 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Code de la facture normalisée</label>
                                                <input type="text" name="code" placeholder="Code MECeF/DGI"
                                                    minlength="24" maxlength="24" id="code" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            @if ($cashbox->statut == 0)
                                                <div class="alert alert-warning alert-dismissible fade show"
                                                    role="alert">
                                                    <strong>Caisse fermée - </strong> Veuillez ouvrir la caisse avant de
                                                    procéder à
                                                    l'encaissement.
                                                </div>
                                            @else
                                                @if (getOnlineUser()->can('view-cashier'))
                                                    <button type="button" onclick="updateStatus()"
                                                        class="btn btn-success mb-3"><i class="mdi mdi-cash"></i>
                                                        Terminer la facture</button>
                                                @endif

                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif

                    <div class="row mt-2">
                        <div style="border: 1px solid black;">
                            <p class="m-0 p-1">
                                <b>Note importante :</b>
                                Les résultats de vos analyses seront disponibles dans un délai de 3 semaines. Selon la
                                complexité du cas, les résultats peuvent être disponibles plus tôt ou plus tard. Vous serez
                                notifiés dès que les résultats seront prêts. Nous vous remercions de votre compréhension et
                                de
                                votre patience.
                            </p>
                        </div>
                    </div>

                    <page_footer>
                        <table style="width: 100%; margin-top:1em !important; text-align :center; justify-content:center;">
                            <tr>
                                <td style="width: 100%; font-size:12px; text-align :center; justify-content:center;">
                                    {{ App\Models\SettingApp::where('key', 'report_footer')->first()->value }}
                                </td>
                            </tr>
                        </table>
                    </page_footer>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('extra-js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        var invoice = @json($invoice);


        var ROUTEVALIDATEPAYMENT = "{{ route('invoice.updatePayment') }}"
        var TOKENVALIDATEPAYMENT = "{{ csrf_token() }}"
        var ROUTECANCELINVOICE = "{{ route('invoice.cancelInvoice') }}"
        var TOKENCANCELINVOICE = "{{ csrf_token() }}"
        var ROUTECONFIRMINVOICE = "{{ route('invoice.confirmInvoice') }}"
        var TOKENCONFIRMINVOICE = "{{ csrf_token() }}"
        var ROUTEINVOICEINDEX = "{{ route('invoice.index') }}"
        var ROUTEINVOICESHOW = "{{ route('invoice.show', ':id') }}";
    </script>
    <script>
        function updateStatus(id) {
            var code = $('#code').val();
            var payment = $('#payment').val();
            var baseUrl = "{{ url('/') }}";

            if (code == "") {
                toastr.error("Code normalisé requis", 'Code normalisé');
            } else if (code.length < 24 || code.length > 24) {
                toastr.error("Code normalisé doit être 24 caractères", 'Code normalisé');
            } else {
                $.ajax({
                    url: baseUrl + "/invoices/checkCode/",
                    type: "GET",
                    data: {
                        code: code,
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.code == 0) {
                            $.ajax({
                                url: baseUrl + "/invoices/updateStatus/" + invoice.id,
                                type: "GET",
                                data: {
                                    code: code,
                                    payment: payment,
                                },
                                success: function(response) {
                                    console.log(response);

                                    window.location.href = ROUTEINVOICEINDEX;

                                },
                                error: function(response) {
                                    console.log('error', response);
                                }
                            })
                        } else {
                            toastr.error("Ce Code normalisé existe déjà", 'Code normalisé')
                        }
                    },
                    error: function(response) {
                        console.log('error', response);
                    }
                })


            }
        }

        function paymentMethod() {
            // Code verification des informations a entree par l'utilisateur
            var paymentMethodChecked = $('input[name="payment_method"]:checked').length > 0;
            var paymentNumber = $('#payment_number').val();
            var amountPayer = $('#amount_payer').val();
            var errorMessage = '';

            if (!paymentMethodChecked) {
                errorMessage += 'Veuillez sélectionner une méthode de paiement.\n';
            }
            if (paymentNumber.length !== 8) {
                errorMessage += 'Veuillez entrer un numéro de téléphone valide de 8 chiffres.\n';
            }
            if (!amountPayer) {
                errorMessage += 'Le montant à payer est requis.\n';
            }

            if (errorMessage) {
                $('#errorMessage').text(errorMessage).show();
            } else {
                $('#errorMessage').hide();
                // Soumettre le formulaire ou exécuter la logique d'encaissement
                console.log('Form is valid. Proceed with submission.');


                // Sélectionner les cases à cocher
                const mtnCheckbox = document.getElementById('mtn');
                const moovCheckbox = document.getElementById('moov');

                // Récupérer la valeur de la case à cocher cochée
                let paymentMethod = '';
                if (mtnCheckbox.checked) {
                    paymentMethod = mtnCheckbox.value;
                } else if (moovCheckbox.checked) {
                    paymentMethod = moovCheckbox.value;
                }

                var invoice_id = $('#invoice_id').val();
                var amount_payer = $('#amount_payer').val();
                var payment_number = $('#payment_number').val();
                var payment_method = paymentMethod;
                var fee = $('#fee').val();

                // console.log(payment_number, payment_method, amount_payer, invoice_id);

                $.ajax({
                    url: baseUrl + "/invoices/payment/store/storejs",
                    type: "GET",
                    data: {
                        invoice_id: invoice_id,
                        amount_payer: amount_payer,
                        payment_number: payment_number,
                        payment_method: payment_method,
                        fee: fee,
                    },
                    success: function(response) {
                        console.log(response);

                        // Vérifiez si la réponse contient "SUCCESS"
                        if (response.message === "INITIATED") {
                            // Mettre à jour le paragraphe avec le message de réussite
                            $('#messageinitiation1').text(
                                "#1 - Paiement initié, en attente de confirmation du client").css('color',
                                '#FFA500');
                            $('#messageinitiation2').text("#2 - Le paiement est en attente de confirmation.")
                                .css('color', 'blue');
                            $('#messageinitiation3').text("");
                            $('#encaisserButton').css('display', 'none');
                            $('#encaisserButton1').css('display', 'none');
                            $('#checkPaymentStatusBtn').css('display', 'block');
                        } else if (response.message === "FAILED") {
                            // Mettre à jour le paragraphe avec le message d'échec
                            $('#messageinitiation').text(
                                    "Échec de l'initiation du paiement. Réessayez ou contactez le support.")
                                .css('color', 'red');
                        }
                    },
                    error: function(response) {
                        console.log('error', response);
                    }
                });
            }
        }





        $(document).ready(function() {
            $('input[name="payment_method"]').change(function() {
                if (this.checked) {
                    $('input[name="payment_method"]').not(this).prop('checked', false);
                }
            });
        });




        function checkPaymentStatus() {
            var invoice_id = $('#invoice_id').val();
            console.log(invoice_id);
            $.ajax({
                url: baseUrl + "/invoices/payment/check/payement",
                type: "GET",
                data: {
                    invoice_id: invoice_id,
                },
                success: function(response) {
                    console.log(response);

                    // Vérifiez si la réponse contient "SUCCESS"
                    if (response.message === "PENDING") {
                        // Mettre à jour le paragraphe avec le message de réussite
                        $('#messageinitiation1').text(
                            "#1 - Paiement initié, en attente de confirmation du client").css('color',
                            '#FFA500');
                        $('#messageinitiation2').text("#2 - Le paiement est en attente de confirmation.").css(
                            'color', 'blue');
                        $('#messageinitiation3').text("");
                        $('#encaisserButton').css('display', 'none');

                        $('#checkPaymentStatusBtn').css('display', 'block');
                        $('#checkPaymentStatusBtn1').css('display', 'none');
                        $('#encaisserButton1').css('display', 'none');


                    } else if (response.message === "SUCCESS") {
                        // Mettre à jour le paragraphe avec le message d'échec
                        $('#messageinitiation1').text(
                            "#1 - Paiement initié, en attente de confirmation du client").css('color',
                            '#FFA500');
                        $('#messageinitiation2').text("#2 - Le paiement est en attente de confirmation.").css(
                            'color', 'blue');
                        $('#messageinitiation3').text("#3 - Le paiement a été traité avec succès.").css('color',
                            'green');
                        $('#checkPaymentStatusBtn').css('display', 'none');
                        $('#encaisserButton1').css('display', 'none');

                    } else if (response.message === "FAILED") {
                        // Mettre à jour le paragraphe avec le message d'échec
                        $('#checkPaymentStatusBtn').css('display', 'none');
                        $('#messageinitiation1').text(
                            "#1 - Paiement initié, en attente de confirmation du client").css('color',
                            '#FFA500');
                        $('#messageinitiation2').text("#2 - Le paiement est en attente de confirmation.").css(
                            'color', 'blue');
                        $('#messageinitiation3').text("#3 - Le paiement a été échoué, réessayer.").css('color',
                            'red');
                        $('#encaisserButton').css('display', 'block');
                        $('#encaisserButton1').css('display', 'block');
                    }
                },
                error: function(response) {
                    console.log('error', response);
                }
            });
        }



        $(document).ready(function() {
            checkPaymentStatus();
        });
    </script>


    <script>
        // Sélectionner les cases à cocher
        const mtnCheckbox = document.getElementById('mtn');
        const moovCheckbox = document.getElementById('moov');

        // Ajouter un écouteur d'événements de clic à la case à cocher MTN
        mtnCheckbox.addEventListener('click', function() {
            // Si la case à cocher MTN est cochée, décocher la case à cocher Moov
            if (mtnCheckbox.checked) {
                moovCheckbox.checked = false;
            }
        });

        // Ajouter un écouteur d'événements de clic à la case à cocher Moov
        moovCheckbox.addEventListener('click', function() {
            // Si la case à cocher Moov est cochée, décocher la case à cocher MTN
            if (moovCheckbox.checked) {
                mtnCheckbox.checked = false;
            }
        });
    </script>


    <script src="{{ asset('viewjs/invoice/show.js') }}"></script>
@endpush
