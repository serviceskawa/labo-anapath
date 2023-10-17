<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Affectation | {{ config('app.name', 'Labocaap') }}</title>
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
                                <img src="{{ $setting ? Storage::url($setting->logo) : '' }}" alt=""
                                    height="100">
                            </div>
                            <div class="float-start mt-3">
                                <h4 class="m-0 ">Affectation de comptes rendu {{ $assignment->code }}</h4>
                            </div>
                        </div>

                        <!-- Invoice Detail-->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="float-left mt-3">
                                    <address>
                                        <strong>Docteur :</strong>
                                        {{ $assignment->user->fullname() }}<br>
                                    </address>
                                </div>

                            </div><!-- end col -->
                            {{-- <div class="col-sm-4 offset-sm-2">
                                <div class="mt-3 float-sm-end">
                                    <p class="font-13"><strong>Date: </strong> {{$invoice->created_at}}</p>
                                    @if ($settingInvoice != null)
                                        @if ($settingInvoice->status == 1)
                                            <p class="font-13"><strong>Type de payment: </strong> {{$invoice->payment}}</p>
                                        @endif
                                    @endif
                                    <p class="font-13"><strong>Code: </strong> <span
                                            class="float-end">{{$invoice->code}}</span>

                                </div>
                            </div><!-- end col --> --}}
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table mt-4">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Demande d'examen</th>
                                                <th>Note</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($details as $key => $item)
                                                <tr>
                                                    <td>{{ $key + 1 }} </td>
                                                    <td>
                                                        <b>{{ $item->test_order_code }}</b>
                                                    </td>
                                                    <td>{{ $item->note }}</td>
                                                </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div> <!-- end table-responsive-->
                            </div> <!-- end col -->
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
        $(document).ready(function() {
                    window.addEventListener("load", (event) => {
                        console.log('aa')
                        window.print()
                    });
        })
    </script>

</body>

</html>
