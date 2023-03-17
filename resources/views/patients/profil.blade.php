@extends('layouts.app2')

@section('title', 'Profil')

@section('content')

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
                    <h4 class="page-title">Dossier Profile</h4>
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

                <div class="row">
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
                <!-- end row -->


                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Historique Factures</h4>

                        <div class="table-responsive">
                            <table id="datatable1" class="table table-hover table-centered mb-0">
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

                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Examen anapath</h4>

                        <div class="table-responsive">
                            <table id="datatable2" class="table table-hover table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Code</th>
                                        <th>Contrat</th>
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
                                            <td> {{$testorder->contrat->name}} </td>
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

                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Consultations</h4>

                        <div class="table-responsive">
                            <table id="datatable3" class="table table-hover table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Code</th>
                                        <th>Docteur</th>
                                        <th>Actions</th>


                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($consultations as $key => $consultation)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td> {{$consultation->created_at}} </td>
                                            <td> {{$consultation->code}} </td>
                                            <td> {{$consultation->doctor->firstname}}.' '.{{$consultation->doctor->lastname}} </td>
                                            <td><a type="button" href="' . route('consultation.show', $data->id) . '" class="btn btn-primary" title="Voir les détails"><i class="mdi mdi-eye"></i></a></td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div> <!-- end table responsive-->
                    </div> <!-- end col-->
                </div> <!-- end row-->

                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Demande de prestation</h4>

                        <div class="table-responsive">
                            <table id="datatable4" class="table table-hover table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Code</th>
                                        <th>Contrat</th>
                                        <th>Montant</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($prestationOrders as $key => $prestationOrder)
                                        <tr>
                                            <td> {{$key+1}} </td>
                                            <td> {{$prestationOrder->created_at}} </td>
                                            <td> {{$prestationOrder->code}} </td>
                                            <td> {{$prestationOrder->contrat->name}} </td>
                                            <td> {{$prestationOrder->total}} </td>
                                            <td><span class="bg-{{$prestationOrder->invoice->paid !=1 ? 'danger' : 'success' }} badge
                                                float-end">{{$prestationOrder->invoice->paid ==1 ? "Payé" : "En
                                                    attente"}}
                                                </span></td>
                                            <td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </div> <!-- end table responsive-->
                    </div> <!-- end col-->
                </div> <!-- end row-->

            </div>
            <!-- end col -->

        </div>
        <!-- end row -->

    </div> <!-- container -->

@endsection

@push('extra-js')
    <script>
        /* DATATABLE */
        $(document).ready(function() {

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

            $('#datatable2').DataTable({
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

            $('#datatable3').DataTable({
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

            $('#datatable4').DataTable({
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

        });
    </script>
@endpush
