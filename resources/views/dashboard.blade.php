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
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Hyper</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">CRM</li>
                        </ol>
                    </div>
                    <h4 class="page-title">CRM</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">

            <div class="col-lg-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Campaign Sent">Patients</h5>
                                <h3 class="my-2 py-1">{{$patients}}</h3>
                                <p class="mb-0 text-muted">
                                    <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 3.27%</span>
                                </p>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <div id="campaign-sent-chart" data-colors="#727cf5"></div>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col -->

            <div class="col-lg-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="New Leads">Contrats</h5>
                                <h3 class="my-2 py-1">{{$contrats}}</h3>
                                <p class="mb-0 text-muted">
                                    <span class="text-danger me-2"><i class="mdi mdi-arrow-down-bold"></i> 5.38%</span>
                                </p>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <div id="new-leads-chart" data-colors="#0acf97"></div>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col -->

            <div class="col-lg-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Deals">Examens</h5>
                                <h3 class="my-2 py-1">{{$tests}}</h3>
                                <p class="mb-0 text-muted">
                                    <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 4.87%</span>
                                </p>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <div id="deals-chart" data-colors="#727cf5"></div>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col -->

            <div class="col-lg-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Deals">Prestations</h5>
                                <h3 class="my-2 py-1">{{$prestations}}</h3>
                                <p class="mb-0 text-muted">
                                    <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 4.87%</span>
                                </p>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <div id="deals-chart" data-colors="#727cf5"></div>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col -->

        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="header-title mb-3">Revenue</h4>

                        <div class="chart-content-bg">
                            <div class="row text-center">
                                <div class="col-md-6">
                                    <p class="text-muted mb-0 mt-3">Aujourd'hui</p>
                                    <h2 class="fw-normal mb-3">
                                        <span>{{ $totalToday }}F CFA</span>
                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-0 mt-3">Ce Mois</p>
                                    <h2 class="fw-normal mb-3">
                                        <span>{{$totalMonth}} F CFA</span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card body-->
                </div>
                <!-- end card -->
            </div>
            <!-- end col-->
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">

                        <h4 class="header-title mb-1">Demande d'Examens</h4>

                        <div class="row text-center mt-2">
                            <div class="col-md-4">
                                <i class="mdi mdi-send widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h3 class="fw-normal mt-3">
                                    <span>{{$testOrdersCount}}</span>
                                </h3>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-warning"></i> Total</p>
                            </div>
                            <div class="col-md-4">
                                <i class="mdi mdi-flag-variant widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h3 class="fw-normal mt-3">
                                    <span>{{$noFinishTest}}</span>
                                </h3>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-primary"></i> En attente</p>
                            </div>
                            <div class="col-md-4">
                                <i class="mdi mdi-email-open widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h3 class="fw-normal mt-3">
                                    <span>{{$finishTest}}</span>
                                </h3>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-success"></i> Terminée</p>
                            </div>
                        </div>
                    </div>
                    <!-- end card body-->
                </div>
                <!-- end card -->
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">

                        <h4 class="header-title mb-1">Demande de Prestations</h4>


                        <div class="row text-center mt-2">
                            <div class="col-md-4">
                                <i class="mdi mdi-send widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h3 class="fw-normal mt-3">
                                    <span>{{$prestationOrderCount}}</span>
                                </h3>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-warning"></i> Total</p>
                            </div>
                            <div class="col-md-4">
                                <i class="mdi mdi-flag-variant widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h3 class="fw-normal mt-3">
                                    <span>{{$finishPrestation}}</span>
                                </h3>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-primary"></i> Payé</p>
                            </div>
                            <div class="col-md-4">
                                <i class="mdi mdi-email-open widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h3 class="fw-normal mt-3">
                                    <span>{{$noFinishPrestation}}</span>
                                </h3>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-success"></i> Non payé</p>
                            </div>
                        </div>
                    </div>
                    <!-- end card body-->
                </div>
                <!-- end card -->
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="header-title mb-3">Demande d'examen terminée aujourd'hui</h4>

                        <div class="table-responsive">
                            <table table id="datatable1" class="table table-hover table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Code</th>
                                        <th>Patiens</th>
                                        <th>Examen</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($testOrdersToday as $testOrderToday)
                                        @if ($testOrderToday->report->is_deliver==1)
                                            <tr>
                                                <td>
                                                    {{$testOrderToday->created_at}}
                                                </td>
                                                <td>{{$testOrderToday->code}}</td>
                                                <td>{{$testOrderToday->patient->lastname}} {{$testOrderToday->patient->firstname}}</td>
                                                {{-- <td>{{$testOrderToday->test->name}}</td> --}}
                                                <td class="table-action">
                                                    <a type="button" href="{{route('details_test_order.index', $testOrderToday->id)}}" class="btn btn-primary" title="Voir les détails"><i class="mdi mdi-eye"></i></a>
                                                </td>
                                            </tr>
                                        @else

                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive-->

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div>
            <!-- end col-->

            {{-- <div class="col-xl-6 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Recent Leads</h4>

                        <div class="d-flex align-items-start">
                            <img class="me-3 rounded-circle" src="assets/images/users/avatar-2.jpg" width="40" alt="Generic placeholder image">
                            <div class="w-100 overflow-hidden">
                                <span class="badge badge-warning-lighten float-end">Cold lead</span>
                                <h5 class="mt-0 mb-1">Risa Pearson</h5>
                                <span class="font-13">richard.john@mail.com</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mt-3">
                            <img class="me-3 rounded-circle" src="assets/images/users/avatar-3.jpg" width="40" alt="Generic placeholder image">
                            <div class="w-100 overflow-hidden">
                                <span class="badge badge-danger-lighten float-end">Lost lead</span>
                                <h5 class="mt-0 mb-1">Margaret D. Evans</h5>
                                <span class="font-13">margaret.evans@rhyta.com</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mt-3">
                            <img class="me-3 rounded-circle" src="assets/images/users/avatar-4.jpg" width="40" alt="Generic placeholder image">
                            <div class="w-100 overflow-hidden">
                                <span class="badge badge-success-lighten float-end">Won lead</span>
                                <h5 class="mt-0 mb-1">Bryan J. Luellen</h5>
                                <span class="font-13">bryuellen@dayrep.com</span>
                            </div>
                        </div>



                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card-->
            </div> --}}
            <!-- end col -->
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-6">
                <div class="card">
                    <div class="card-body">

                        <h4 class="header-title mb-4">Les rendez-vous d'aujourd'hui</h4>

                        <div class="table-responsive">
                            <table table id="datatable1" class="table table-hover table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Patiens</th>
                                        <th>Docteur</th>
                                        <th>Priorité</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($appointements as $appointement)
                                            <tr>
                                                <td>
                                                    {{$appointement->date}}
                                                </td>
                                                <td>{{$appointement->patient->lastname}} {{$testOrderToday->patient->firstname}}</td>
                                                <td>{{$appointement->doctor->lastname}} {{$appointement->doctor->firstname}}</td>
                                                <td>{{$appointement->priority}}</td>

                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive-->

                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card-->
            </div>
            <!-- end col -->
        </div>

    </div> <!-- container -->
@endsection

@push('extra-je')
    <script>
        $('#datatable1').DataTable({
                "order": [
                    [0, "asc"]
                ],
                "columnDefs": [{
                    "targets": [0],
                    "searchable": false
                }],
                "language": {
                    "lengthMenu": "Afficher _MENU_ enregistrements par page",
                    "zeroRecords": "Aucun enregistrement disponible",
                    "info": "Afficher page _PAGE_ sur _PAGES_",
                    "infoEmpty": "Aucun enregistrement disponible",
                    "infoFiltered": "(filtré à partir de _MAX_ enregistrements au total)",
                    "sSearch": "Rechercher:",
                    "paginate": {
                        "previous": "Précédent",
                        "next": "Suivant"
                    }
                },
            });
    </script>
@endpush
