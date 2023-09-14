@extends('layouts.app2')

@section('title', 'Patient')

@section('content')
@include('layouts.alerts')

    <!-- Start Content-->
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{ route('patients.index') }}" type="button" class="btn btn-primary">Retour à la liste des
                            patients</a>
                    </div>
                    <h4 class="page-title">Dossier Patient</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-4">
                <!-- Personal-Information -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mt-0 mb-3">Informations personnelles</h4>

                        <hr>

                        <div class="text-start">
                            <p class="text-muted"><strong>Nom :</strong> <span class="ms-2">{{ $patient->firstname }}</span></p>

                            <p class="text-muted"><strong>Prénoms :</strong><span class="ms-2">{{ $patient->lastname }}</span></p>

                            <p class="text-muted"><strong>Code patient :</strong><span class="ms-2">{{ $patient->code }}</span></p>

                            <p class="text-muted"><strong>Téléphone :</strong><span class="ms-2">{{ $patient->telephone1 }}</span></p>

                            <p class="text-muted"><strong>Âge :</strong> <span class="ms-2">{{ $patient->age }} {{ $patient->year_or_month !=1 ? 'mois' : 'ans' }} </span></p>

                            <p class="text-muted"><strong>Adresse :</strong> <span class="ms-2">{{ $patient->adresse }}</span></p>

                        </div>
                    </div>
                </div>
                <!-- Personal-Information -->
            </div> <!-- end col-->

            <div class="col-xl-8">

                <div class="row" style="margin-top: 100px;" >
                    <div class="col-sm-4">
                        <div class="card tilebox-one">
                            <div class="card-body">
                                <i class="dripicons-basket float-end text-muted"></i>
                                <h6 class="text-muted text-uppercase mt-0 mb-2">Total</h6>
                                <h3 class="m-b-20 pb-3 pr-2">{{$total}} F CFA</h3>
                                {{-- <span class="badge bg-primary"> +11% </span> <span class="text-muted">From previous period</span> --}}
                            </div> <!-- end card-body-->
                        </div> <!--end card-->
                    </div><!-- end col -->

                    <div class="col-sm-4">
                        <div class="card tilebox-one">
                            <div class="card-body">
                                <i class="dripicons-box float-end text-muted"></i>
                                <h6 class="text-muted text-uppercase mt-0 mb-2">Payé</h6>
                                <h3 class="m-b-20 pb-3 pr-2"><span>{{$paye}}</span> F CFA</h3>
                                {{-- <span class="badge bg-danger"> -29% </span> <span class="text-muted">From previous period</span> --}}
                            </div> <!-- end card-body-->
                        </div> <!--end card-->
                    </div><!-- end col -->

                    <div class="col-sm-4">
                        <div class="card tilebox-one">
                            <div class="card-body">
                                <i class="dripicons-jewel float-end text-muted"></i>
                                <h6 class="text-muted text-uppercase mt-0 mb-2">Non payé</h6>
                                <h3 class="m-b-20 pb-3 pr-2">{{$nopaye}} F CFA</h3>
                                {{-- <span class="badge bg-primary"> +89% </span> <span class="text-muted">Last year</span> --}}
                            </div> <!-- end card-body-->
                        </div> <!--end card-->
                    </div><!-- end col -->

                </div>

            </div>
            <!-- end col -->

                <div class="row">


                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title mb-3">Demandes d'examen</h4>

                                <div class="table-responsive">
                                    <table id="datatable2" class="table table-hover table-centered mb-0">

                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Code</th>
                                                <th>Montant</th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>

                                        <tbody>

                                            @foreach ($testorders as $key =>$testorder)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td> {{$testorder->created_at}} </td>
                                                    <td> {{$testorder->code}} </td>
                                                    <td> {{$testorder->total}} </td>
                                                    <td>
                                                        <a type="button" href="{{route('details_test_order.index', $testorder->id)}}" class="btn btn-primary" title="Voir les détails"><i class="mdi mdi-eye"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>

                                </div> <!-- end table responsive-->

                            </div> <!-- end col-->
                        </div> <!-- end row-->
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title mb-3">Factures</h4>

                                <div class="table-responsive">
                                    <table id="datatable2" class="table table-hover table-centered mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Code</th>
                                                <th>Total</th>
                                                <th>Remise</th>
                                                <th>Statut</th>
                                                <th>Actions</th>

                                            </tr>
                                        </thead>

                                        <tbody>

                                            @foreach ($invoices as $key =>$invoice)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $invoice->date }}</td>
                                                    <td>{{$invoice->code !=null ? $invoice->code : ''}}</td>
                                                    <td>{{ $invoice->subtotal }}</td>
                                                    <td>{{ $invoice->total }}</td>
                                                    <td><span class="bg-{{$invoice->paid != 1 ? 'danger' : 'success' }} badge
                                                        float-end">{{$invoice->paid == 1 ? "Payé" : "En
                                                            attente"}}
                                                        </span></td>
                                                    <td>
                                                        <a type="button" href="{{route('invoice.show',$invoice->id)}}" class="btn btn-warning"><i
                                                                class="mdi mdi-eye"></i> </a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>

                                </div> <!-- end table responsive-->

                            </div> <!-- end col-->
                        </div> <!-- end row-->
                    </div>

                </div>

        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection

@push('extra-js')
   <script src="{{asset('viewjs/patient/profil.js')}}"></script>
@endpush
