@extends('layouts.app2')

@section('title', 'Details Facture')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Reçu de paiement {{ $invoice->order?'de '.$invoice->order->code:'' }}</h4>
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
                            <img src="{{ $setting ? Storage::url($setting->logo) : '' }}" alt="" height="18">
                        </div>
                        <div class="float-end">
                            <h4 class="m-0 d-print-none">Reçu de paiement</h4>
                        </div>
                    </div>

                    <!-- Invoice Detail-->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="float-left mt-3">
                                <h4>Informations du Patient</h4>
                                <address>
                                    <strong>Nom: </strong> {{ $invoice->client_name }}<br>
                                    <strong>Addresse: </strong> {{ $invoice->client_address }}<br>
                                </address>
                            </div>

                        </div><!-- end col -->
                        <div class="col-sm-4 offset-sm-2">
                            <div class="mt-3 float-sm-end">
                                <p class="font-13"><strong>Date: </strong> {{ $invoice->created_at }}</p>
                                <p class="font-13"><strong>Status: </strong>
                                    <span
                                        class="bg-{{ $invoice->paid != 1 ? 'danger' : 'success' }} badge
                                    float-end">{{ $invoice->paid == 1
                                        ? 'Payé'
                                        : "En
                                                                                                            attente" }}
                                    </span>
                                </p>
                                <p class="font-13"><strong>Code: </strong> <span
                                        class="float-end">{{ $invoice->code }}</span>
                                </p>
                                @if ($invoice->order != null)
                                    <p class="font-13"><strong>Contrat: </strong> <span
                                            class="float-end">{{ $invoice->order->contrat->name }}</span>
                                    </p>
                                @endif
                            </div>
                        </div><!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table mt-4">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Titre de l'examen</th>
                                            <th>Quantité</th>
                                            <th>Prix(F CFA)</th>
                                            <th>Remise(F CFA)</th>
                                            <th class="text-end">Total(F CFA)</th>
                                        </tr>
                                    </thead>
                                    <tbody>

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
                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive-->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-sm-6">
                            {{-- <div class="clearfix pt-3">
                            <h6 class="text-muted">Notes:</h6>
                            <small>
                                Une facture emise n'est ni reprise ni modifiée
                            </small>
                        </div> --}}
                        </div> <!-- end col -->
                        <div class="col-sm-6">
                            <div class="float-end mt-3 mt-sm-0">
                                <p><b>Sous-total: </b>
                                    <span
                                        class="float-end">{{ number_format(abs($invoice->subtotal), 0, ',', ' ') }}</span>
                                </p>
                                <h3><b>Total: </b>{{ number_format(abs($invoice->total), 0, ',', ' ') }}</h3>
                            </div>
                            <div class="clearfix"></div>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row-->


                    @if ($settingInvoice != null && $invoice->paid !=1)
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
                                                    <option {{ $invoice->payment == 'CHEQUES' ? 'selected' : '' }}
                                                        value="CHEQUES">CHEQUES</option>
                                                    <option {{ $invoice->payment == 'MOBILEMONEY' ? 'selected' : '' }}
                                                        value="MOBILEMONEY">MOBILE MONEY</option>
                                                    <option {{ $invoice->payment == 'CARTEBANCAIRE' ? 'selected' : '' }}
                                                        value="CARTEBANCAIRE">CARTE BANQUAIRE</option>
                                                    <option {{ $invoice->payment == 'VIREMENT' ? 'selected' : '' }}
                                                        value="VIREMENT">VIREMENT</option>
                                                    <option {{ $invoice->payment == 'CREDIT' ? 'selected' : '' }}
                                                        value="CREDIT">CREDIT</option>
                                                    <option {{ $invoice->payment == 'AUTRE' ? 'selected' : '' }}
                                                        value="AUTRE">AUTRE</option>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Code de la facture normalisée</label>
                                                {{-- <input type="text" class="form-control" id="invoice_total"
                                                    value="{{ number_format(abs($invoice->total), 0, ',', ' ') }}"
                                                    readonly> --}}
                                                <input type="text" name="code" placeholder="Code MECeF/DGI" minlength="24" maxlength="24" id="code" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            @foreach (getRolesByUser(Auth::user()->id) as $role)
                                                @if ($role->name == "Caissier")
                                                        <button type="button" onclick="updateStatus()" class="btn btn-success mb-3"><i
                                                            class="mdi mdi-cash"></i>
                                                        Terminer la facture</button>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                            </div>


                        {{-- @if ($invoice->paid == 1 && $settingInvoice->status == 1)
                            <div class="row"
                                style="border: ridge;border-radius: 5px;border-color: rgb(119, 147, 234);padding: 5px;">

                                <div class="col-lg-8" style="display: flex; padding-left:75px;">
                                    <div id="qrcode"></div>
                                </div>
                                <div class="col-lg-4">
                                    <div style="text-align: center">Code MECeFDGI <br> {{ $invoice->codeMecef }} </div>
                                    <div style="display:flex; justify-content:space-between"><span>MECef NIM:</span>
                                        <span>{{ $invoice->nim }}</span>
                                    </div>
                                    <div style="display:flex; justify-content:space-between"><span>MECef Compteurs :</span>
                                        {{ $invoice->counters }} </div>
                                    <div style="display:flex; justify-content:space-between"><span>MECef Heure :</span>
                                        {{ $invoice->dategenerate }} </div>
                                </div>
                            </div>
                        @endif --}}

                    @endif

                    {{-- <div class="d-print-none text-end mt-4">
                        <div class="text-end" style="float: right;">

                            @if ($invoice->paid != 1)

                                @if ($settingInvoice != null)
                                    @if ($settingInvoice->status != 1)
                                        @foreach (getRolesByUser(Auth::user()->id) as $role)
                                            @if ($role->name == "Caissier")
                                                   <div class="d-flex" style="width: 500px;">
                                                       <div>
                                                        <button type="button" onclick="updateStatus({{$invoice->id}})" class="btn btn-success" st><i class="mdi mdi-cash"></i>
                                                            Marqué comme Payé</button>

                                                       </div>
                                                   </div>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach (getRolesByUser(Auth::user()->id) as $role)
                                            @if ($role->name == "Caissier")

                                                    <button type="button" onclick="invoicebtn()" class="btn btn-success"><i
                                                        class="mdi mdi-cash"></i>
                                                    Terminer la facture</button>

                                            @endif
                                        @endforeach
                                    @endif
                                @endif

                            @endif

                        </div>
                    </div> --}}
                    <!-- end buttons -->


                </div> <!-- end card-body-->
            </div> <!-- end card -->
        </div> <!-- end col-->
    </div>
    <!-- end row -->
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
    </script>
    <script>
        function updateStatus(id) {
    var code = $('#code').val();
    var payment = $('#payment').val();
    if (code == "") {
        toastr.error("Code normalisé requis",'Code normalisé');
    }else if(code.length < 24 || code.length > 24)
    {
        toastr.error("Code normalisé doit être 24 caractères",'Code normalisé');
    }else{
        $.ajax({
            url: baseUrl + "/invoices/checkCode/",
            type: "GET",
            data: {
                code: code,
            },
            success: function(response) {
                console.log(response);
                if(response.code == 0)
                {
                    $.ajax({
                        url: baseUrl + "/invoices/updateStatus/"+ invoice.id,
                        type: "GET",
                        data: {
                            code: code,
                            payment: payment
                        },
                        success: function(response) {
                            // alert(response.code);
                            console.log(response);
                            window.location.href = ROUTEINVOICEINDEX;
                            // location.reload();
                        },
                        error: function(response) {
                            console.log('error', response);
                        }
                    })
                }else
                {
                    toastr.error("Ce Code normalisé existe déjà",'Code normalisé')
                }
            },
            error: function(response) {
                console.log('error', response);
            }
        })


    }
}
    </script>

    <script src="{{ asset('viewjs/invoice/show.js') }}"></script>
@endpush
