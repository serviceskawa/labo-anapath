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
                            <div class="">
                                <h4 class="m-0 d">ID {{ $item->code }}</span>
                                    : {{ $item->created_at
                                    }} - {{ $item->updated_at }}</h4>
                            </div>
                        </div>



                        <div class="row">
                            <div class="">
                                <div class="card">
                                    <div class="card-body">
                                        {{-- <h4 class="header-title mb-3">Recaputilatif</h4> --}}

                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Mode de paiement</th>
                                                        <th>Fond initial</th>
                                                        <th>Vente</th>
                                                        <th>Solde</th>
                                                        <th>Comptage</th>
                                                        <th>Ecart</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Especes</td>
                                                        <td>{{ $item->opening_balance }}</td>
                                                        <td>{{ $item->cash_calculated }}</td>
                                                        <td>{{ $item->opening_balance + $item->cash_calculated }}</td>
                                                        <td>{{ $item->cash_confirmation }}</td>
                                                        <td>{{ $item->cash_ecart }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Mobile Money</td>
                                                        <td>-</td>
                                                        <td>{{ $item->mobile_money_calculated }}</td>
                                                        <td>-</td>
                                                        <td>{{ $item->mobile_money_confirmation }}</td>
                                                        <td>{{ $item->mobile_money_ecart }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Cheques</td>
                                                        <td>-</td>
                                                        <td>{{ $item->cheque_calculated }}</td>
                                                        <td>-</td>
                                                        <td>{{ $item->cheque_confirmation }}</td>
                                                        <td>{{ $item->total_ecart }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Virement</td>
                                                        <td>-</td>
                                                        <td>{{ $item->virement_calculated }}</td>
                                                        <td>-</td>
                                                        <td>{{ $item->virement_confirmation }}</td>
                                                        <td>{{ $item->virement_ecart }}</td>
                                                    </tr>

                                                    <tr style="font-weight: 900;">
                                                        <td>Total</td>
                                                        <td>{{ $item->opening_balance }}</td>
                                                        <td>{{ $item->total_calculated }}</td>
                                                        <td>{{ $item->opening_balance + $item->cash_calculated }}</td>
                                                        <td>{{ $item->total_confirmation }}</td>
                                                        <td>{{ $item->total_ecart }}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <label for="" class="form-label">Commentaire</label>
                                            <input value="{{ $item->description }}" readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6" style="font-size: 20px; font-weight:900;">
                                SOLDE DE FERMETURE : {{ $item->close_balance}}
                            </div>
                        </div>



                    </div> <!-- end card-body-->
                </div> <!-- end card -->
            </div> <!-- end col-->
        </div>
    </div>

    <!-- bundle -->
    <script src="{{ asset('/adminassets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('/adminassets/js/app.min.js') }}"></script>
    <script>
         window.addEventListener("load", (event) => {
            console.log('aa')
            window.print()
        });
    </script>



</body>

</html>
