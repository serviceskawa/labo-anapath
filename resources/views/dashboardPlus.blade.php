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
        @if (getOnlineUser()->can('view-admin-dashboard'))
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

                <div class="col-lg-6">
                    <div class="card"   style="padding-bottom: 20px;">
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


            <div class="row" style="margin-left: 5px;margin-right:5px">
                <h4 class="header-title mt-2 mb-2">STATISTIQUE MENSUELLE</h4>
                <div class="card">
                    <div class="card-body">
                        <div class="card  mb-3">
                            <div class="card-body">
                                EXAMENS DEMANDES
                                <div class="row mt-3">
                                    <div class="col-sm-6 col-xl-4">
                                        <div class="card shadow-none m-0 border-start">
                                            <div class="card-body text-center">
                                                <i class="dripicons-checklist text-muted" style="font-size: 24px;"></i>
                                                <h3><span>{{$nombreTests}}</span></h3>
                                                <p class="text-muted font-15 mb-0">Total d'examens</p>
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="col-sm-6 col-xl-4">
                                        <div class="card shadow-none m-0 border-start">
                                            <div class="card-body text-center">
                                                <i class="dripicons-user-group text-muted" style="font-size: 24px;"></i>
                                                <h3><span>{{$c_a_tests}}</span></h3>
                                                <p class="text-muted font-15 mb-0">Chiffre d'affaire</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <div class="card shadow-none m-0 border-start">
                                            <div class="card-body text-center">
                                                <i class="dripicons-user-group text-muted" style="font-size: 24px;"></i>
                                                <h3><span>{{$totalPatientTest}}</span></h3>
                                                <p class="text-muted font-15 mb-0">Patients</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card-body-->
                        </div>



                        <div class="card mb-3">
                            <div class="card-body ">
                                STATISTIQUE PATIENTS
                                
                                                 
                                <div class="row mt-3">

                                    <div class="col-xl-4 col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="header-title mt-1 mb-3">Hôpitaux</h4>
        
                                                <div class="table-responsive">
                                                    <table id="scroll-vertical-datatable" class="table table-sm table-centered mb-0 font-14">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Hôpital</th>
                                                                <th>Patients</th>
                                                                <th style="width: 40%;"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($totalDemandesParHopital as $patient)
                                                                <tr>
                                                                    <td>{{$patient->nom_hopital}}</td>
                                                                    <td> {{$patient->total_patients}} </td>
                                                                    <td>
                                                                        <div class="progress" style="height: 3px;">
                                                                            <div class="progress-bar" role="progressbar" style="width: {{($patient->total_patients/$totalDemandesParHopitalCount)*100}}%; height: 20px;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                           
                                                        </tbody>
                                                    </table>
                                                </div> <!-- end table-responsive-->
                                            </div> <!-- end card-body-->
                                        </div> <!-- end card-->
                                    </div> <!-- end col-->

                                    <div class="col-xl-4 col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                {{-- <a href="" class="p-0 float-end">Export <i class="mdi mdi-download ms-1"></i></a> --}}
                                                <h4 class="header-title mt-1 mb-3">Médécin traitant</h4>
        
                                                <div class="table-responsive">
                                                    <table id="scroll-vertical-datatable1" class="table table-sm table-centered mb-0 font-14">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Médécin</th>
                                                                <th>Patients</th>
                                                                <th style="width: 40%;"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($totalDemandesParMedecin as $medecin)
                                                            @if($medecin->total_patients>5)
                                                                <tr>
                                                                    <td>{{$medecin->nom_medecin}}</td>
                                                                    <td>{{$medecin->total_patients}}</td>
                                                                    <td>
                                                                        <div class="progress" style="height: 3px;">
                                                                            <div class="progress-bar" role="progressbar" style="width: {{ ($medecin->total_patients/$totalDemandesParMedecinCount)*100 }}%; height: 20px;" aria-valuenow="{{$medecin->total_patients}}" aria-valuemin="0" aria-valuemax="{{$totalDemandesParMedecinCount}}"></div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div> <!-- end table-responsive-->
                                            </div> <!-- end card-body-->
                                        </div> <!-- end card-->
                                    </div> <!-- end col-->

                                    <div class="col-xl-4 col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="header-title mt-1 mb-3">Type de demande</h4>
        
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-centered mb-0 font-14">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Type</th>
                                                                <th>Patients</th>
                                                                <th style="width: 40%;"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($totalDemandesParType as $type)
                                                                <tr>
                                                                    <td>{{$type->nom_type}}</td>
                                                                    <td>{{$type->total_patients}}</td>
                                                                    <td>
                                                                        <div class="progress" style="height: 3px;">
                                                                            <div class="progress-bar" role="progressbar" style="width: {{($type->total_patients/$totalDemandesParTypeCount)*100}}%; height: 20px;" aria-valuenow="{{$type->total_patients}}" aria-valuemin="0" aria-valuemax="{{$totalDemandesParTypeCount}}"></div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div> <!-- end table-responsive-->
                                            </div> <!-- end card-body-->
                                        </div> <!-- end card-->
                                    </div> <!-- end col-->
        
        
                                </div>
                                <!-- end row -->
                            </div> <!-- end card-body-->
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div>



            @if (getOnlineUser()->can('view-dashbord-finance'))
                <div class="row" style="display: flex; justify-content:space-between">
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

                    <div class="col-xl-4 col-lg-12 order-lg-1">
                        <div class="card" style="padding-bottom: 95px">
                            <div class="card-body">

                                <h4 class="header-title">FACTURES</h4>

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
            @endif


            <div class="row">
                {{-- Statistique par docteur --}}
                <div class="col-xl-6 col-lg-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title mb-3">Statistique par docteurs</h4>
                            <div class="table-responsive">
                                <table table id="datatable1" class="table table-hover table-centered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Docteurs</th>
                                            <th>Demandes Affectées</th>
                                            <th>Demandes Traitées</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($doctorDatas as $doctorData)
                                                <tr>
                                                    <td>
                                                        {{ $doctorData['doctor'] }}
                                                    </td>
                                                    <td>
                                                        {{ $doctorData['assigne'] }}
                                                    </td>

                                                    <td>
                                                        {{ $doctorData['traite'] }}
                                                    </td>
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

                {{-- utilisateur connecté --}}
                <div class="col-xl-6 col-lg-6">
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($loggedInUserIds as $key => $userID)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    {{ $userID->lastname }} {{ $userID->firstname }}
                                                    {{ $userID->id == Auth::user()->id ? '(Vous)' : '' }}
                                                </td>
                                                <td> {{ $userID->email }}</td>
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
        @endif

        {{-- Examen terminé aujourd'hi --}}
        @if (getOnlineUser()->can('view-secretariat-dashboard'))
            <div class="row">

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
                                            @if ($testOrderToday->is_deliver == 1)
                                                <tr>
                                                    <td>
                                                        {{ $testOrderToday->order->created_at }}
                                                    </td>
                                                    <td>{{ $testOrderToday->order->code }}</td>
                                                    <td>{{ $testOrderToday->patient->lastname }}
                                                        {{ $testOrderToday->patient->firstname }}</td>
                                                    <td class="table-action">
                                                        @if ($testOrderToday->status != 1)
                                                            <a type="button"
                                                                href="{{ route('details_test_order.index', $testOrderToday->id) }}"
                                                                class="btn btn-warning" title="Compte rendu"><i
                                                                    class="uil-file-medical"></i> </a>;
                                                            <button type="button" onclick="deleteModal($testOrderToday->id)"
                                                                class="btn btn-danger" title="Supprimer"><i
                                                                    class="mdi mdi-trash-can-outline"></i> </button>;
                                                        @else
                                                            <a type="button"
                                                                href="{{ route('report.show', $testOrderToday->id) }}"
                                                                class="btn btn-warning" title="Compte rendu"><i
                                                                    class="uil-file-medical"></i> </a>
                                                        @endif

                                                        @if (!empty($testOrderToday->invoice->id))
                                                            <a type="button"
                                                                href="{{ route('invoice.show', $testOrderToday->invoice->id) }}"
                                                                class="btn btn-success" title="Facture"><i
                                                                    class="mdi mdi-printer"></i> </a>
                                                        @else
                                                            <a type="button"
                                                                href="{{ route('invoice.storeFromOrder', $testOrderToday->id) }}"
                                                                class="btn btn-success" title="Facture"><i
                                                                    class="mdi mdi-printer"></i> </a>
                                                        @endif
                                                        @if (!empty($testOrderToday))
                                                            @if ($testOrderToday->status == 1)
                                                                <a type="button" target="_blank"
                                                                    href="{{ route('report.updateDeliver', $testOrderToday->id) }}"
                                                                    class="btn btn-warning"
                                                                    title="Imprimer le compte rendu"><i
                                                                        class="mdi mdi-printer"></i> Imprimer </a>
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

            </div>
        @endif

        @if (getOnlineUser()->can('view-pathologist-dashboard'))
            <div class="row">
                <div class="col-12">
                    <div class="card widget-inline">
                        <div class="card-body p-0">
                            <div class="row g-0">
                                <div class="col-sm-6 col-xl-6">
                                    <div class="card shadow-none m-0">
                                        <div class="card-body text-center">
                                            <i class="dripicons-briefcase text-muted" style="font-size: 24px;"></i>
                                            <h3><span>{{$testOrdersByDoctorCount}}</span></h3>
                                            <p class="text-muted font-15 mb-0">Total de demandes d'examen affectées</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xl-6">
                                    <div class="card shadow-none m-0 border-start">
                                        <div class="card-body text-center">
                                             <i class="dripicons-graph-line text-muted" style="font-size: 24px;"></i>
                                            <h3><span>0</span><i class="mdi mdi-arrow-up text-success"></i></h3></h3>
                                            <p class="text-muted font-15 mb-0">Productivité</p>
                                        </div>
                                    </div>
                                </div>

                            </div> <!-- end row -->
                        </div>
                    </div> <!-- end card-box-->
                </div> <!-- end col-->
            </div>
            <!-- end row-->


            <div class="row">
                <div class="col-lg-4">
                    <div class="card" style="padding-bottom: 105px;">
                        <div class="card-body">

                            <h4 class="header-title mb-5">Status d'examens</h4>

                            <div class="my-4 chartjs-chart" style="height: 202px;">
                                <canvas id="project-status-chart-doctor" data-colors="#0acf97,#727cf5,#fa5c7c"></canvas>
                            </div>

                            <div class="row text-center mt-2 py-2">
                                <div class="col-6">
                                    <i class="mdi mdi-trending-up text-success mt-3 h3"></i>
                                    <h3 class="fw-normal">
                                        <span>{{ $totalByStatusForDoctor ? count($totalByStatusForDoctor)>1 ? $totalByStatusForDoctor[1]['total'] :'' :0 }}</span>
                                    </h3>
                                    <p class="text-muted mb-0">Terminé</p>
                                </div>
                                <div class="col-6">
                                    <i class="mdi mdi-trending-down text-dander mt-3 h3"></i>
                                    <h3 class="fw-normal">
                                        <span><span>{{ $totalByStatusForDoctor ? count($totalByStatusForDoctor)>1 ? $totalByStatusForDoctor[0]['total'] :'' : 0 }}</span></span>
                                    </h3>
                                    <p class="text-muted mb-0">En attente</p>
                                </div>
                            </div>
                            <!-- end row-->

                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title mb-3">Demande affectées</h4>
                            <p><b>{{$totalByStatusForDoctor ? count($totalByStatusForDoctor)>1 ? $totalByStatusForDoctor[1]['total'] :'' :''}}</b> Demandes d'examen terminées sur {{$testOrdersByDoctorCount}} </p>

                            <div class="table-responsive">
                                <table  id="scroll-vertical-datatable" class="table table-centered table-nowrap table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Code</th>
                                            <th>Patient</th>
                                            <th>Compte rendu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($testOrdersByDoctors)
                                            @foreach ($testOrdersByDoctors as $test)
                                            <tr>
                                                <td>
                                                    <span> {{$test->created_at}} </span>
                                                </td>
                                                <td>
                                                    <span> {{$test->code}} </span>
                                                    <a href="{{route('details_test_order.index',$test->id)}}" style="color: rgb(126, 122, 122)"><i class="mdi mdi-eye"></i></a>
                                                </td>

                                                <td>
                                                    <span> {{$test->patient->firstname . ' ' . $test->patient->lastname}} </span>
                                                </td>
                                                <td>
                                                        @switch($test->report->status)
                                                            @case(1)
                                                            <span class="badge badge-success-lighten"> {{'Terminé';}} </span>
                                                                @break

                                                            @default
                                                            <span class="badge badge-warning-lighten"> {{'En attente';}} </span>
                                                        @endswitch
                                                        <a type="button" href="{{route('report.show', $test->report->id)}}" title="Compte rendu"><i class="uil-file-medical"></i> </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif

                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive-->

                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
            <!-- end row-->


            <div class="row">

                <div class="col-xl-6">
                    <div class="card">

                        <div class="card-body">

                            <h4 class="header-title mb-3">Activités récentes</h4>

                            <div class="table-responsive">
                                <table  id="scroll-vertical-datatable" class="table table-centered table-nowrap table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Code</th>
                                            <th>Patient</th>
                                            <th>Compte rendu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($testOrdersByDoctorsToday)
                                            @foreach ($testOrdersByDoctorsToday as $test)
                                            <tr>
                                                <td>
                                                    <span> {{$test->created_at}} </span>
                                                </td>
                                                <td>
                                                    <span> {{$test->code}} </span>
                                                    <a href="{{route('details_test_order.index',$test->id)}}" style="color: rgb(126, 122, 122)"><i class="mdi mdi-eye"></i></a>
                                                </td>

                                                <td>
                                                    <span> {{$test->patient->firstname . ' ' . $test->patient->lastname}} </span>
                                                </td>
                                                <td>
                                                        @switch($test->report->status)
                                                            @case(1)
                                                            <span class="badge badge-success-lighten"> {{'Terminé';}} </span>
                                                                @break

                                                            @default
                                                            <span class="badge badge-warning-lighten"> {{'En attente';}} </span>
                                                        @endswitch
                                                        <a type="button" href="{{route('report.show', $test->report->id)}}" title="Compte rendu"><i class="uil-file-medical"></i> </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif

                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive-->

                        </div> <!-- end card body-->

                    </div> <!-- end card -->
                </div><!-- end col-->

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title mb-3">Calendrier</h4>

                            <div class="row">
                                <div class="col-md-7">
                                    <div data-provide="datepicker-inline" data-date-today-highlight="true" class="calendar-widget"></div>
                                </div> <!-- end col-->
                                <div class="col-md-5">
                                    <ul class="list-unstyled">
                                        @if ($appointments)
                                            @forelse ($appointments as $appointment)
                                                <li class="mb-4">
                                                    <p class="text-muted mb-1 font-13">
                                                        <i class="mdi mdi-calendar"></i> {{$appointment->date}}
                                                    </p>
                                                    <h5> Patient : {{$appointment->patient->firstname}} {{$appointment->patient->lastname}} </h5>
                                                    <h5>
                                                        Priorité:
                                                        @switch($appointment->priority)
                                                            @case('normal')
                                                                <span class="badge badge-light-lighten">Normal</span>
                                                                @break
                                                            @case('urgent')
                                                                <span class="badge badge-warning-lighten">Urgent</span>
                                                                @break

                                                            @default
                                                                <span class="badge badge-ga,ger-lighten">Très urgent</span>

                                                        @endswitch

                                                    </h5>
                                                </li>
                                            @empty
                                                <h5>Aucun rendez-vous</h5>
                                            @endforelse
                                        @endif

                                    </ul>
                                </div> <!-- end col -->
                            </div>
                            <!-- end row -->

                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->

            </div>
            <!-- end row-->
        @endif



    </div><!-- container -->

@endsection

@push('extra-js')
    <script>
        var baseUrl = "{{ url('/') }}";
        var statusTestOrder = {!! json_encode($totalByStatus) !!}
        var totalByStatusForDoctor = {!! json_encode($totalByStatusForDoctor) !!}
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
