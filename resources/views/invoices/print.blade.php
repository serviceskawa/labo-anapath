<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>@yield('title') | {{ config('app.name', 'Labocaap') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App css -->
    <link href="{{ asset('/adminassets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/adminassets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style">
    <link href="{{ asset('/adminassets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style">

    <style>
        @media print {
            .toPrint {
                page-break-after: always;
            }

            @page {
                margin: 10mm 10mm 0 10mm;
            }

            body {
                margin: 0mm;
            }

            .print-header {
                /* position: fixed; */
                /* top: -10mm; */
            }

            footer {
                position: fixed;
                bottom: 10mm;
                text-align: center !important;
                font-size: 10px;
            }
        }
    </style>
</head>

<body class="loading"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <!-- Begin page -->
    <div class="wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Invoice Logo-->
                        <div class="clearfix">
                            <div class="float-start mb-3">
                                <img src="{{ $setting ? Storage::url($setting->logo) : '' }}" alt="" height="100">
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
                                        <strong>Nom: </strong> {{$invoice->client_name}}<br>
                                        <strong>Addresse: </strong> {{$invoice->client_address}}<br>
                                    </address>
                                </div>

                            </div><!-- end col -->
                            <div class="col-sm-4 offset-sm-2">
                                <div class="mt-3 float-sm-end">
                                    <p class="font-13"><strong>Date: </strong> {{$invoice->created_at}}</p>
                                    @if ($settingInvoice!=null)
                                        @if ($settingInvoice->status ==1)
                                            <p class="font-13"><strong>Type de payment: </strong> {{$invoice->payment}}</p>
                                        @endif
                                    @endif
                                    <p class="font-13"><strong>Code: </strong> <span
                                            class="float-end">{{$invoice->code}}</span>

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
                                                <th class="text-end">Total(F CFA)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoice->details as $key => $item)
                                            <tr>
                                                <td>{{$key+1}} </td>
                                                <td>
                                                    <b>{{$item->test_name}}</b>
                                                </td>
                                                <td>1</td>
                                                <td>{{$item->price}}</td>
                                                <td class="text-end">{{$item->total}}</td>
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
                                        Toutes mes transactions
                                    </small>
                                </div> --}}
                            </div> <!-- end col -->
                            <div class="col-sm-6">
                                <div class="float-end mt-3 mt-sm-0">
                                    <p><b>Sous-total: </b>
                                        <span class="float-end">{{ number_format(abs($invoice->subtotal), 0, ',', '
                                            ')}}</span>
                                    </p>

                                    <h3><b>Total: </b>{{ number_format(abs($invoice->total), 0, ',', ' ')}}</h3>
                                </div>
                                <div class="clearfix"></div>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row-->

                        @if ($settingInvoice!=null)
                            @if ($invoice->qrcode && $settingInvoice->status ==1)

                                <div class="row">

                                    <p style="font-style: italic">­­­---ELÉMENTS DE SÉCURITÉ DE LA FACTURE NORMALISÉE­­­---</p>
                                    <hr style="margin-left: 5px; margin-right: 5px;">
                                    <div class="col-6" style="display: flex; padding-left:75px;">
                                        <div id="qrcode"></div>
                                    </div>
                                    <div class="col-6">
                                        <div style="text-align: center">Code MECeFDGI <br> {{$invoice->codeMecef}} </div>
                                        <div style="display:flex; justify-content:space-between"><span>MECef NIM:</span> <span>{{$invoice->nim}}</span> </div>
                                        <div style="display:flex; justify-content:space-between"><span>MECef Compteurs :</span> {{$invoice->counters}} </div>
                                        <div style="display:flex; justify-content:space-between"><span>MECef Heure :</span> {{$invoice->dategenerate}} </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                    </div> <!-- end card-body-->
                </div> <!-- end card -->
            </div> <!-- end col-->
        </div>
    </div>

    <!-- bundle -->
    <script src="{{ asset('/adminassets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('/adminassets/js/app.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        var invoice = {!! json_encode($invoice) !!}
    </script>
    <script src="{{asset('viewjs/invoice/print.js')}}"></script>

</body>

</html>
