@extends('layouts.app2')

@section('title', 'Dashbord')

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">

                    </div>
                    <h4 class="page-title">Tableau de bord</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-12 col-lg-12">

                <div class="row">
                    <div class="col-lg-3">
                        <div class="card widget-flat">
                            <div class="card-body">
                                <div class="float-end">
                                    <i class="mdi mdi-account-multiple widget-icon"></i>
                                </div>
                                <h5 class="text-muted fw-normal mt-0" title="Number of Customers">PATIENTS</h5>
                                <h3 class="mt-3 mb-3">{{ $valeurPatient }}</h3>
                                <p class="mb-0 text-muted">
                                    <span class="{{$crPatient>=0 ? 'text-success':'text-danger'}} me-2">
                                        @if ($crPatient>=0)
                                            <i class="mdi mdi-arrow-up-bold"></i>
                                        @else
                                            <i class="mdi mdi-arrow-down-bold"></i>
                                        @endif
                                         {{$crPatient}}%
                                    </span>
                                    <span class="text-nowrap">Depuis le mois passé</span>
                                </p>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-lg-3">
                        <div class="card widget-flat">
                            <div class="card-body">
                                <div class="float-end">
                                    <i class="mdi mdi-cart-plus widget-icon"></i>
                                </div>
                                <h5 class="text-muted fw-normal mt-0" title="Number of Orders">CLIENTS PRO.</h5>
                                <h3 class="mt-3 mb-3"> {{$valeurClient}} </h3>
                                <p class="mb-0 text-muted">
                                    <span class="{{$crClient>=0 ? 'text-success':'text-danger'}} me-2">
                                        @if ($crClient>=0)
                                            <i class="mdi mdi-arrow-up-bold"></i>
                                        @else
                                            <i class="mdi mdi-arrow-down-bold"></i>
                                        @endif
                                         {{$crClient}}%
                                    </span>
                                    <span class="text-nowrap">Depuis le mois passé</span>
                                </p>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-lg-3">
                        <div class="card widget-flat">
                            <div class="card-body">
                                <div class="float-end">
                                    <i class="mdi mdi-pulse widget-icon"></i>
                                </div>
                                <h5 class="text-muted fw-normal mt-0" title="Growth">DEMANDE D'EXAMEN</h5>
                                <h3 class="mt-3 mb-3">{{$valeurTestOrder}}</h3>
                                <p class="mb-0 text-muted">
                                    <span class="{{$crTestOrder>=0 ? 'text-success':'text-danger'}} me-2">
                                        @if ($crTestOrder>=0)
                                            <i class="mdi mdi-arrow-up-bold"></i>
                                        @else
                                            <i class="mdi mdi-arrow-down-bold"></i>
                                        @endif
                                         {{$crTestOrder}}%
                                    </span>
                                    <span class="text-nowrap">Depuis le mois passé</span>
                                </p>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-lg-3">
                        <div class="card widget-flat">
                            <div class="card-body">
                                <div class="float-end">
                                    <i class="mdi mdi-currency-usd widget-icon"></i>
                                </div>
                                <h5 class="text-muted fw-normal mt-0" title="Average Revenue">CHIFFRE D'AFFAIRES</h5>
                                <h3 class="mt-3 mb-3">{{formatMontant($valeurInvoice)}}</h3>
                                <p class="mb-0 text-muted">
                                    <span class="{{$crInvoice>=0 ? 'text-success':'text-danger'}} me-2">
                                        @if ($crInvoice>=0)
                                            <i class="mdi mdi-arrow-up-bold"></i>
                                        @else
                                            <i class="mdi mdi-arrow-down-bold"></i>
                                        @endif
                                         {{$crInvoice}}%
                                    </span>
                                    <span class="text-nowrap">Depuis le mois passé</span>
                                </p>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->


                </div> <!-- end row -->

            </div> <!-- end col -->

        </div>
        <!-- end row -->

        <div class="row" style="display: flex">

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">CHIFFRE D'AFFAIRES</h4>

                        <div class="chart-content-bg">
                            <div class="row text-center">
                                <div class="col-md-6">
                                    <p class="text-muted mb-0 mt-3">Semaine actuelle</p>
                                    <h2 class="fw-normal mb-3">
                                        <small
                                            class="mdi mdi-checkbox-blank-circle text-primary align-middle me-1"></small>
                                        <span>{{ formatMontant($totalForCurrentWeek) }}</span>
                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-0 mt-3">Semaine précédente</p>
                                    <h2 class="fw-normal mb-3">
                                        <small
                                            class="mdi mdi-checkbox-blank-circle text-success align-middle me-1"></small>
                                        <span>{{ formatMontant($totalForLastWeek) }}</span>
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <div class="dash-item-overlay d-none d-md-block" dir="ltr">
                            <h5>Aujourd'hui: {{formatMontant($totalToday)}}</h5>

                            <a href="{{route('invoice.business')}}" class="btn btn-outline-primary">View Statements
                                <i class="mdi mdi-arrow-right ms-2"></i>
                            </a>
                        </div>
                        <div dir="ltr">
                            <div id="revenue-chart" class="apex-charts mt-3" data-colors="#727cf5,#0acf97"></div>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->


            <div class="col-lg-4">
                <div class="card"   style="padding-bottom: 95px;">
                    <div class="card-body">

                        <h4 class="header-title mb-4">STATUT D'EXAMENS</h4>

                        <div class="my-4 chartjs-chart" style="height: 202px;">
                            <canvas id="project-status-chart" data-colors="#0acf97,#E52D4F"></canvas>
                        </div>


                        <div class="row text-center mt-5 py-2">
                            <div class="col-6">
                                <i class="mdi mdi-trending-up text-success mt-3 h3"></i>
                                <h3 class="fw-normal">
                                    <span>{{ count($totalByStatus)>1 ? $totalByStatus[1]['total'] :'' }}</span>
                                </h3>
                                <p class="text-muted mb-0">Terminé</p>
                            </div>
                            <div class="col-6">
                                <i class="mdi mdi-trending-down text-danger mt-3 h3"></i>
                                <h3 class="fw-normal">
                                    <span>{{ $totalByStatus[0]['total'] }}</span>
                                </h3>
                                <p class="text-muted mb-0">En attente</p>
                            </div>
                        </div>
                        <!-- end row-->
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->

        </div>



        <div class="row" style="display: flex; justify-content:space-between">
            <div class="col-xl-6 col-lg-12 order-lg-2 order-xl-1">
                <div class="card">
                    <div class="card-body">

                        <h4 class="header-title mt-2 mb-3">EXAMENS LES PLUS DEMANDÉS</h4>

                        <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <tbody>
                                @for ($i = 0; $i < 7 && $i < count($examensDemandes); $i++)
                                    @php
                                        $examen = $examensDemandes[$i];
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $i+1 }}
                                        </td>
                                        <td>
                                            <h5 class="font-14 my-1 fw-normal">{{ $examen->test_name }}</h5>
                                        </td>
                                        <td>
                                            <h5 class="font-14 my-1 fw-normal">{{ $examen->total_demandes }}</h5>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>

                        </div> <!-- end table-responsive-->
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->

            <div class="col-xl-6 col-lg-12 order-lg-1">
                <div class="card" style="padding-bottom: 15px">
                    <div class="card-body">

                        <h4 class="header-title">Total Sales</h4>

                        <div id="average-sales-test" class="apex-charts mb-4 mt-4"
                            data-colors="#727cf5,#0acf97,#fa5c7c,#ffbc00"></div>


                        <div class="chart-widget-list">
                            <p>
                                <i class="mdi mdi-square text-success"></i> Factures de vente payées
                                <span class="float-end" id="invoicePaid"></span>
                            </p>
                            <p>
                                <i class="mdi mdi-square text-warning"></i> Factures de vente payées
                                <span class="float-end" id="invoiceNoPaid"></span>
                            </p>
                            <p>
                                <i class="mdi mdi-square text-primary"></i> Factures d'avoir payées
                                <span class="float-end" id="refundPaid"></span>
                            </p>
                            <p class="mb-0">
                                <i class="mdi mdi-square text-danger"></i> Factures d'avoir non payées
                                <span class="float-end" id="refundNoPaid"></span>
                            </p>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->


        </div>
        <!-- end row -->
    </div><!-- container -->

@endsection

@push('extra-js')
    <script>
        var baseUrl = "{{ url('/') }}";
        var statusTestOrder = {!! json_encode($totalByStatus) !!}
    </script>
    <script src="{{ asset('viewjs/home.js') }}"></script>
    <script src="{{asset('adminassets/js/vendor/apexcharts.min.js')}}"></script>
    <script src="{{asset('adminassets/js/vendor/jquery-jvectormap-1.2.2.min.js')}}"></script>
    <script src="{{asset('adminassets/js/vendor/jquery-jvectormap-world-mill-en.js')}}"></script>
    <script src="{{asset('adminassets/js/vendor/Chart.bundle.min.js')}}"></script>
    <script src="{{asset('adminassets/js/pages/demo.dashboard-projects.js')}}"></script>
    <script src="{{asset('adminassets/js/pages/demo.dashboard.js')}}"></script>
    {{-- <script src="{{asset('viewjs/dashboard.js')}}"></script> --}}
@endpush
