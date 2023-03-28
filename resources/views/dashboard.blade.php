@extends('layouts.app2')

@section('title', 'Dashbord')

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Tableau de bord</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">

            {{-- Patients --}}
            <div class="col-lg-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Campaign Sent">Patients</h5>
                                <h3 class="my-2 py-1">{{$patients}}</h3>
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


            {{-- Contrats --}}
            <div class="col-lg-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="New Leads">Contrats</h5>
                                <h3 class="my-2 py-1">{{$contrats}}</h3>
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


            {{-- Examens --}}
            <div class="col-lg-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Deals">Examens</h5>
                                <h3 class="my-2 py-1">{{$tests}}</h3>
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

        {{-- Revenue --}}
        {{-- <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="header-title mb-3">Revenue</h4>

                        <div class="chart-content-bg">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <p class="text-muted mb-0 mt-3">Aujourd'hui</p>
                                    <h2 class="fw-normal mb-3">
                                        <span>{{ $totalToday }}F CFA</span>
                                    </h2>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-muted mb-0 mt-3">Ce Mois</p>
                                    <h2 class="fw-normal mb-3">
                                        <span>{{$totalMonth}} F CFA</span>
                                    </h2>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-muted mb-0 mt-3">Mois précedent</p>
                                    <h2 class="fw-normal mb-3">
                                        <span>{{$totalLastMonth}} F CFA</span>
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
        </div> --}}

        {{-- Demande d'Examens --}}
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="header-title mb-1">Demande d'Examens</h4>

                    <div class="row text-center mt-2">
                        <div class="col-md-3">
                            <h3 class="fw-normal mt-3">
                                <span>{{$testOrdersCount}}</span>
                            </h3>
                            <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-warning"></i> Total</p>
                        </div>
                        <div class="col-md-3">
                            <h3 class="fw-normal mt-3">
                                <span>{{$finishTest}}</span>
                            </h3>
                            <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-success"></i> Terminée</p>
                        </div>
                        <div class="col-md-3">
                            <h3 class="fw-normal mt-3">
                                <span>{{$noFinishTest}}</span>
                            </h3>
                            <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-primary"></i> En attente</p>
                        </div>
                        <div class="col-md-3">
                            <h3 class="fw-normal mt-3">
                                <span>{{$noFinishWeek}}</span>
                            </h3>
                            <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-primary"></i> En attente plus de 3 semaines</p>
                        </div>

                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>

        <div class="row">

                {{-- Examen terminé aujourd'hi --}}

                <div class="col-xl-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title mb-3">Comptes rendu dsponible aujourd'hui</h4>

                            <div class="table-responsive">
                                <table table id="datatable1" class="table table-hover table-centered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Code</th>
                                            <th>Patiens</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($testOrdersToday as $testOrderToday)
                                            @if ($testOrderToday->is_deliver==1)
                                                <tr>
                                                    <td>
                                                        {{$testOrderToday->order->created_at}}
                                                    </td>
                                                    <td>{{$testOrderToday->order->code}}</td>
                                                    <td>{{$testOrderToday->patient->lastname}} {{$testOrderToday->patient->firstname}}</td>
                                                    <td class="table-action">
                                                        @if ($testOrderToday->status !=1)
                                                            <a type="button" href="{{route('details_test_order.index', $testOrderToday->id)}}" class="btn btn-warning" title="Compte rendu"><i class="uil-file-medical"></i> </a>;
                                                            <button type="button" onclick="deleteModal($testOrderToday->id)" class="btn btn-danger" title="Supprimer"><i class="mdi mdi-trash-can-outline"></i> </button>;
                                                        @else
                                                            <a type="button" href="{{route('report.show', $testOrderToday->id)}}" class="btn btn-warning" title="Compte rendu"><i class="uil-file-medical"></i> </a>
                                                        @endif

                                                        @if (!empty($testOrderToday->invoice->id))
                                                        <a type="button" href="{{route('invoice.show', $testOrderToday->invoice->id)}}" class="btn btn-success" title="Facture"><i class="mdi mdi-printer"></i> </a>
                                                        @else
                                                        <a type="button" href="{{route('invoice.storeFromOrder', $testOrderToday->id)}}" class="btn btn-success" title="Facture"><i class="mdi mdi-printer"></i> </a>
                                                        @endif
                                                        @if (!empty($testOrderToday))
                                                            @if ($testOrderToday->status ==1)
                                                                <a type="button" target="_blank" href="{{route('report.updateDeliver',  $testOrderToday->id)}}" class="btn btn-warning" title="Imprimer le compte rendu"><i class="mdi mdi-printer"></i> Imprimer </a>
                                                            @endif
                                                        @endif
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

                {{-- Agenda d'aujourd'hui --}}
                {{-- <div class="col-xl-6 col-lg-6">
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
                                                    <td>{{$appointement->doctor_interne->lastname}} {{$appointement->doctor_interne->firstname}}</td>
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
                </div> --}}
                <!-- end col -->

        </div>


       @if (getOnlineUser()->can('view-dashbord-finance'))
             <!-- tasks panel -->
            <div class="mt-2 mb-3">
                <h5 class="m-0 pb-2">
                    <a class="text-dark" data-bs-toggle="collapse" href="#todayTasks" role="button" aria-expanded="false" aria-controls="todayTasks">
                        <i class='uil uil-angle-down font-18'></i>Revenu</span>
                    </a>
                </h5>

                <div class="collapse" id="todayTasks">
                    <div class="card">
                        <div class="card-body">
                            <div class="chart-content-bg">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <p class="text-muted mb-0 mt-3">Aujourd'hui</p>
                                        <h2 class="fw-normal mb-3">
                                            <span>{{ $totalToday }}F CFA</span>
                                        </h2>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="text-muted mb-0 mt-3">Ce Mois</p>
                                        <h2 class="fw-normal mb-3">
                                            <span>{{$totalMonth}} F CFA</span>
                                        </h2>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="text-muted mb-0 mt-3">Mois précedent</p>
                                        <h2 class="fw-normal mb-3">
                                            <span>{{$totalLastMonth}} F CFA</span>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end .collapse-->
            </div> <!-- end .mt-2-->
       @endif


        {{-- utilisateur connecté --}}
        <div class="row">
            <div class="col-xl-12 col-lg-6">
                <div class="card">
                    <div class="card-body">

                        <h4 class="header-title mb-4">Utilisateurs connectés</h4>

                        <div class="table-responsive">
                            <table table id="datatable1" class="table table-hover table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($loggedInUserIds as $key =>$userID)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>
                                                    {{getUserData($userID)->lastname}} {{getUserData($userID)->firstname}} {{ $userID == Auth::user()->id ? "(Vous)" : '' }}
                                                </td>
                                                <td> {{getUserData($userID)->email}}</td>
                                                <td>
                                                    @foreach (getRolesByUser($userID) as $role)
                                                        <span class="bg-primary badge" style="margin-left: 10px;">{{$role->name}}
                                                        </span>
                                                    @endforeach
                                                </td>
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
