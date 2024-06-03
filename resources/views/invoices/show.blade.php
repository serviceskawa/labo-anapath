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

            <h4 class="page-title">{{$invoice->status_invoice !=1 ?'Reçu de paiement':'Facture d\'avoir'}} {{
                $invoice->order ? 'de ' . $invoice->order->code : '' }}</h4>
        </div>
    </div>
</div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-9">
        <div class="card">
            <div class="card-body">

                <!-- Invoice Logo-->
                <div class="clearfix">
                    <div class="float-start mb-3">
                        <img src="{{ $setting ? Storage::url($setting->logo) : '' }}" alt="" height="18">
                    </div>
                    <div class="float-end">
                        <h4 class="m-0 d-print-none">{{$invoice->status_invoice !=1 ?'Reçu de paiement':'Facture
                            d\'avoir'}}</h4>
                    </div>
                </div>

                <!-- Invoice Detail-->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="float-left mt-3">
                            <h4>Informations du Patient</h4>
                            <address>
                                <strong>Nom: </strong> {{ $invoice->client_name }}<br>
                                <strong>Adresse: </strong> {{ $invoice->client_address }}<br>
                            </address>
                        </div>

                    </div><!-- end col -->
                    <div class="col-sm-4 offset-sm-2">
                        <div class="mt-3 float-sm-end">
                            <p class="font-13"><strong>Date: </strong> {{ $invoice->created_at }}</p>
                            <p class="font-13"><strong>Status: </strong>
                                <span class="bg-{{ $invoice->paid != 1 ? 'danger' : 'success' }} badge
                                    float-end">{{ $invoice->paid == 1
                                    ? 'Payé'
                                    : "En
                                    attente" }}
                                </span>
                            </p>
                            <p class="font-13"><strong>Code: </strong> <span class="float-end">{{
                                    $invoice->status_invoice !=1 ? $invoice->code : $refund->code }}</span>
                            </p>
                            {{-- @if ($invoice->order != null) --}}
                            @if ($invoice->status_invoice!=1)
                            <p class="font-13"><strong>Contrat: </strong> <span class="float-end">{{ $invoice->contrat ?
                                    $invoice->contrat->name : ($invoice->order ? ($invoice->order->contrat ?
                                    $invoice->order->contrat->name : ''):'') }}</span>
                            </p>
                            @else
                            <p class="font-13"><strong>Référence: </strong> <span class="float-end">{{ $refund->invoice
                                    ? $refund->invoice->code : '' }}</span>
                            </p>
                            @endif
                            {{-- @endif --}}
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->
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
                                        <th>Designation</th>
                                        <th>Quantité</th>
                                        <th>Prix(F CFA)</th>
                                        <th>Remise(F CFA)</th>
                                        <th class="text-end">Total(F CFA)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($refund)
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $refund ? ($refund->reason ? $refund->reason->description : ''):'' }}
                                        </td>
                                        <td>1</td>
                                        <td>{{ $refund ? $refund->montant :'' }}</td>
                                        <td>0.0 </td>
                                        <td>{{ $refund ? $refund->montant:'' }}</td>
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
                            <p><b>Sous-total: </b>
                                <span class="float-end">{{ number_format(abs($invoice->subtotal), 0, ',', ' ') }}</span>
                            </p>
                            <h3><b>Total: </b>{{ number_format(abs($invoice->total), 0, ',', ' ') }}</h3>
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
                                        value="ESPECES">ESPECES</option>
                                    <option {{ $invoice->payment == 'MOBILEMONEY' ? 'selected' : '' }}
                                        value="MOBILEMONEY">MOBILE MONEY</option>

                                    <option {{ $invoice->payment == 'CHEQUES' ? 'selected' : '' }}
                                        value="CHEQUES">CHEQUES</option>

                                    <option {{ $invoice->payment == 'VIREMENT' ? 'selected' : '' }}
                                        value="VIREMENT">VIREMENT</option>


                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label class="form-label">Code de la facture normalisée</label>

                                <input type="text" name="code" placeholder="Code MECeF/DGI" minlength="24"
                                    maxlength="24" id="code" class="form-control">
                            </div>
                        </div>

                        <!-- Verification caisse ferme ou non -->
                        <div class="col-md-4 col-12">
                            @if ($cashbox->statut==0)
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Caisse fermée - </strong> Veuillez ouvrir la caisse avant de procéder à
                                l'encaissement.
                            </div>
                            @else
                            @if (getOnlineUser()->can('view-cashier'))
                            <button type="button" onclick="updateStatus()" class="btn btn-success mb-3"><i
                                    class="mdi mdi-cash"></i>
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
                        @if($invoiceVerify == null)
                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Type de
                                    paiement</label>
                                <select class="form-select select2" data-toggle="select2" name="payment"
                                    value="{{ $invoice->payment }}" id="payment" required>
                                    <option {{ $invoice->payment == 'ESPECES' ? 'selected' : '' }}
                                        value="ESPECES">ESPECES</option>

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
                                <select class="form-select select2" data-toggle="select2" name="payment" disabled
                                    value="{{ $invoice->payment }}" id="payment" required>

                                    <option {{ $invoiceMethodPayment=='MOBILEMONEY-MTN' ? 'selected' : '' }}
                                        value="MOBILEMONEY-MTN">MOBILE MONEY - MTN</option>



                                    <option {{ $invoiceMethodPayment=='MOBILEMONEY-MOOV' ? 'selected' : '' }}
                                        value="MOBILEMONEY-MOOV">MOBILE MONEY - MOOV</option>

                                </select>
                            </div>
                        </div>
                        @endif


                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label class="form-label">Code de la facture normalisée</label>
                                <input type="text" name="code" placeholder="Code MECeF/DGI" minlength="24"
                                    maxlength="24" id="code" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            @if ($cashbox->statut==0)
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Caisse fermée - </strong> Veuillez ouvrir la caisse avant de procéder à
                                l'encaissement.
                            </div>
                            @else
                            @if (getOnlineUser()->can('view-cashier'))
                            <button type="button" onclick="updateStatus()" class="btn btn-success mb-3"><i
                                    class="mdi mdi-cash"></i>
                                Terminer la facture</button>
                            @endif

                            @endif
                        </div>
                    </div>
                </div>
                @endif
                @endif
                @endif
            </div>
        </div>
    </div>




    {{-- @if ( $invoiceVerify) --}}

    {{-- @endif --}}
    <div class="col-3">
        <div class="card">
            <div class="card-body">

                <div class="clearfix mb-3">
                    <div class="text-start">
                        <h4 class="m-0 d-print-none">Encaisser par Mobile Money</h4>
                    </div>
                </div>



                @if($invoiceVerify && $invoiceVerify->payment_status == "SUCCESS")
                <p style="color: green;">Paiement encaissé le {{ $invoiceVerify->updated_at }} avec le numéro de
                    paiement {{ $invoiceVerify->payment_number }} .</p>
                @elseif($invoice->paid == 1 && $invoiceVerify==null)
                <p style="color: green;"></p>
                @else
                <div class="row mb-3">

                    <div class="row">
                        <div class="col-md-6 mb-3 col-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="mtn" name="payment_method"
                                    value="MOBILEMONEY-MTN">
                                <label class="form-check-label" for="mtn">MTN BENIN</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3 col-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="moov" name="payment_method"
                                    value="MOBILEMONEY-MOOV">
                                <label class="form-check-label" for="moov">Moov BENIN</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3 col-12">
                        <label for="">Numero de telephone</label>
                        <input type="number" name="payment_number" placeholder="Numero de telephone : xxxxxxxx"
                            id="payment_number" value="{{ $invoice->patient->telephone1  ?? old('payment_number') }}" class="form-control"
                            minlength="8" maxlength="8">
                    </div>

                    <div class="col-md-12 mb-3 col-12">
                        <label for="">Montant a payer</label>
                        <input type="number" name="amount_payer" placeholder="Montant a payer"
                            value="{{ $total_a_payer['roundedNumber'] }}" id="amount_payer" class="form-control"
                            readonly>
                    </div>

                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}" id="invoice_id"
                        class="form-control">

                    <input type="hidden" name="fee" value="{{ $total_a_payer['fee'] }}" id="fee" class="form-control">


                    <div class="col-md-12  col-12">
                        <button id="checkPaymentStatusBtn" onclick="checkPaymentStatus()" class="btn btn-primary"
                            style="display: none;">Vérifier
                            le statut du paiement</button>
                    </div>


                    @if($invoiceVerify && ($invoiceVerify->payment_status == "INITIATED" ||
                    $invoiceVerify->payment_status == "PENDING"))

                    <div class="col-md-12 mb-3 col-12">
                        <button id="checkPaymentStatusBtn1" style="display: block;" onclick="checkPaymentStatus()"
                            class="btn btn-primary">Vérifier
                            le statut du paiement</button>
                    </div>

                    @else
                    <div class="col-md-12 col-12">
                        <button type="button" style="display: block;" onclick="paymentMethod()" id="encaisserButton"
                            class="btn btn-warning">Encaisser</button>
                    </div>
                    @endif

                    {{-- <div class="col-md-12 col-12">
                        <button type="button" style="display: none;" onclick="paymentMethod()" id="encaisserButton1"
                            class="btn btn-warning">Encaisser</button>
                    </div> --}}

                    <p id="errorMessage" style="color: red; display: none;"></p>
                    <p id="messageinitiation1"></p>
                    <p id="messageinitiation2"></p>
                    <p id="messageinitiation3"></p>

                </div>
                @endif
            </div>
        </div>
    </div>
</div>



</style>
@endsection

@push('extra-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    var invoice = {!! json_encode($invoice) !!}
        var baseUrl = "{{ url('/') }}";
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

function paymentMethod()
{
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

    console.log(payment_number, payment_method, amount_payer, invoice_id);
    
    $.ajax({
            url: baseUrl + "/invoices/payment/store/storejs",
            type: "GET",
            data: {
                invoice_id: invoice_id,
                amount_payer: amount_payer,
                payment_number : payment_number,
                payment_method : payment_method,
                fee : fee,
            },
            success: function(response) {
                console.log(response);
                                    
               // Vérifiez si la réponse contient "SUCCESS"
                if (response.message === "INITIATED") {
                    // Mettre à jour le paragraphe avec le message de réussite
                    $('#messageinitiation1').text("#1 - Paiement initié, en attente de confirmation du client").css('color', '#FFA500');
                    $('#messageinitiation2').text("#2 - Le paiement est en attente de confirmation.").css('color', 'blue');
                    $('#messageinitiation3').text("");
                    $('#encaisserButton').css('display', 'none');
                    $('#encaisserButton1').css('display', 'none');
                    $('#checkPaymentStatusBtn').css('display', 'block');
                } 
                else  if (response.message === "FAILED") {
                    // Mettre à jour le paragraphe avec le message d'échec
                    $('#messageinitiation').text("Échec de l'initiation du paiement. Réessayez ou contactez le support.").css('color', 'red');
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




function checkPaymentStatus()
{
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
                    $('#messageinitiation1').text("#1 - Paiement initié, en attente de confirmation du client").css('color', '#FFA500');
                    $('#messageinitiation2').text("#2 - Le paiement est en attente de confirmation.").css('color', 'blue');
                    $('#messageinitiation3').text("");
                    $('#encaisserButton').css('display', 'none');

                    $('#checkPaymentStatusBtn').css('display', 'block');
                    $('#checkPaymentStatusBtn1').css('display', 'none');
                    $('#encaisserButton1').css('display', 'none');

                    
                } else  if (response.message === "SUCCESS") {
                    // Mettre à jour le paragraphe avec le message d'échec
                    $('#messageinitiation1').text("#1 - Paiement initié, en attente de confirmation du client").css('color', '#FFA500');
                    $('#messageinitiation2').text("#2 - Le paiement est en attente de confirmation.").css('color', 'blue');
                    $('#messageinitiation3').text("#3 - Le paiement a été traité avec succès.").css('color', 'green');
                    $('#checkPaymentStatusBtn').css('display', 'none');
                    $('#encaisserButton1').css('display', 'none');

                }else  if (response.message === "FAILED") {
                    // Mettre à jour le paragraphe avec le message d'échec
                    $('#checkPaymentStatusBtn').css('display', 'none');
                    $('#messageinitiation1').text("#1 - Paiement initié, en attente de confirmation du client").css('color', '#FFA500');
                    $('#messageinitiation2').text("#2 - Le paiement est en attente de confirmation.").css('color', 'blue');
                    $('#messageinitiation3').text("#3 - Le paiement a été échoué, réessayer.").css('color', 'red');
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