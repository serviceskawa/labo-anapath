@extends('layouts.app2')

@section('title', 'Examens2')

@section('content')
<div class="row">

    <div class="page-title-box">

        <h4 class="page-title">Mon espace [ {{ Auth::user()->firstname .' '. Auth::user()->lastname }} ]</h4>
    </div>

    <div class="">

        {{-- Les blocs pour afficher les resultats --}}
        <div class="row">

            {{-- Patients --}}
            {{-- <div class="col-lg-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-muted  mt-0">Nombre total de cas attribués</h5>
                                <h3 class="my-2 py-1">{{ $nbreTotalCasAttribuer }}</h3>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <div id="campaign-sent-chart" data-colors="#727cf5"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}


            <div class="col-lg-4">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-cart-plus widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Number of Orders">DEMANDES AFFECTÉES</h5>
                        <h3 class="mt-3 mb-3"> {{ $nbreTotalCasAttribuerValeur }} </h3>
                        <p class="mb-0 text-muted">
                            <span class="{{$nbreTotalCasAttribuer>=0 ? 'text-success':'text-danger'}} me-2">
                                @if ($nbreTotalCasAttribuer>=0)
                                    <i class="mdi mdi-arrow-up-bold"></i>
                                @else
                                    <i class="mdi mdi-arrow-down-bold"></i>
                                @endif
                                {{$nbreTotalCasAttribuer}}%
                            </span>
                            <span class="text-nowrap">Depuis le mois passé</span>
                        </p>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->



            {{-- <div class="col-lg-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-muted mt-0" title="Campaign Sent">Nombre de cas en attente</h5>
                                <h3 class="my-2 py-1">{{ $nbreTotalCasEnAttente }}</h3>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <div id="campaign-sent-chart" data-colors="#727cf5"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}


            <div class="col-lg-4">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-cart-plus widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Number of Orders"><a href="#casenattente" style="text-decoration : none; color:inherit;">DEMANDES EN ATTENTE</a></h5>
                        <h3 class="mt-3 mb-3"> {{ $nbreTotalCasEnAttenteValeur }} </h3>
                        <p class="mb-0 text-muted">
                            <span class="{{$nbreTotalCasEnAttente>=0 ? 'text-success':'text-danger'}} me-2">
                                @if ($nbreTotalCasEnAttente>=0)
                                    <i class="mdi mdi-arrow-up-bold"></i>
                                @else
                                    <i class="mdi mdi-arrow-down-bold"></i>
                                @endif
                                {{$nbreTotalCasEnAttente}}%
                            </span>
                            <span class="text-nowrap">Depuis le mois passé</span>
                        </p>
                    </div>
                </div>
            </div>



            {{-- <div class="col-lg-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-muted mt-0" title="Campaign Sent">Nombre de cas terminés</h5>
                                <h3 class="my-2 py-1">{{ $nbreTotalCasEnTerminer }}</h3>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <div id="campaign-sent-chart" data-colors="#727cf5"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}



            <div class="col-lg-4">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-cart-plus widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Number of Orders"> <a href="#casterminer" style="text-decoration : none; color:inherit;">DEMANDES TERMINÉES</a></h5>
                        <h3 class="mt-3 mb-3"> {{ $nbreTotalCasEnTerminerValeur }} </h3>
                        <p class="mb-0 text-muted">
                            <span class="{{$nbreTotalCasEnTerminer>=0 ? 'text-success':'text-danger'}} me-2">
                                @if ($nbreTotalCasEnTerminer>=0)
                                    <i class="mdi mdi-arrow-up-bold"></i>
                                @else
                                    <i class="mdi mdi-arrow-down-bold"></i>
                                @endif
                                {{$nbreTotalCasEnTerminer}}%
                            </span>
                            <span class="text-nowrap">Depuis le mois passé</span>
                        </p>
                    </div>
                </div>
            </div>




            <div class="col-lg-4">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-cart-plus widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Number of Orders">IMMUNOS EN ATTENTE</h5>
                        <h3 class="mt-3 mb-3 text-danger"> {{ $nbreTotalCasImmunoAttenteValeur }} </h3>
                        <p class="mb-0 text-muted">
                            <span class="{{$nbreTotalCasImmunoAttente>=0 ? 'text-success' : 'text-danger'}} me-2">
                                @if ($nbreTotalCasImmunoAttente>=0)
                                    <i class="mdi mdi-arrow-up-bold"></i>
                                @else
                                    <i class="mdi mdi-arrow-down-bold"></i>
                                @endif
                                {{$nbreTotalCasImmunoAttente}}%
                            </span>
                            <span class="text-nowrap">Depuis le mois passé</span>
                        </p>
                    </div>
                </div>
            </div>



            {{-- <div class="col-lg-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-muted mt-0" title="Campaign Sent">Demandes urgentes</h5>
                                <h3 class="my-2 py-1">{{ $nbreTotalCasUrgent }}</h3>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <div id="campaign-sent-chart" data-colors="#727cf5"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}


            <div class="col-lg-4">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-cart-plus widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Number of Orders">DEMANDES URGENTES</h5>
                        <h3 class="mt-3 mb-3 text-danger"> {{ $nbreTotalCasUrgentValeur }} </h3>
                        <p class="mb-0 text-muted">
                            <span class="{{$nbreTotalCasUrgent>=0 ? 'text-success':'text-danger'}} me-2">
                                @if ($nbreTotalCasUrgent>=0)
                                    <i class="mdi mdi-arrow-up-bold"></i>
                                @else
                                    <i class="mdi mdi-arrow-down-bold"></i>
                                @endif
                                {{$nbreTotalCasUrgent}}%
                            </span>
                            <span class="text-nowrap">Depuis le mois passé</span>
                        </p>
                    </div>
                </div>
            </div>


            {{-- Deuxieme lignes --}}
            {{-- <div class="col-lg-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-muted mt-0" title="Campaign Sent">Demandes en retard</h5>
                                <h3 class="my-2 py-1">{{ $nbreTotalCasRetard }}</h3>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <div id="campaign-sent-chart" data-colors="#727cf5"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}


            <div class="col-lg-4">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-cart-plus widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Number of Orders">DEMANDES EN RETARD</h5>
                        <h3 class="mt-3 mb-3 text-danger"> {{ $nbreTotalCasRetardValeur }} </h3>
                        <p class="mb-0 text-muted">
                            <span class="{{$nbreTotalCasRetard>=0 ? 'text-success':'text-danger'}} me-2">
                                @if ($nbreTotalCasRetard>=0)
                                    <i class="mdi mdi-arrow-up-bold"></i>
                                @else
                                    <i class="mdi mdi-arrow-down-bold"></i>
                                @endif
                                {{$nbreTotalCasRetard}}%
                            </span>
                            <span class="text-nowrap">Depuis le mois passé</span>
                        </p>
                    </div>
                </div>
            </div>


            



            































































































            <div class="card mb-md-0 mb-3" id="casenattente">
                <div class="card-body">
                    <div class="card-widgets">
                        <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                        <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                            aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                        <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                    </div>
                    <h5 class="card-title mb-0">Liste des demandes en attente</h5>

                    <div id="cardCollpase1" class="show collapse pt-3">

                        <div class="row mb-3">

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="example-fileinput" class="form-label">Type d'examen</label>
                                    <select name="type_examen" id="type_examen" class="form-control">
                                        <option value="">Tous</option>
                                        @forelse ($types_orders as $type)
                                        <option value="{{ $type->id }}">{{ $type->title }}</option>
                                        @empty
                                        Ajouter un Type d'examen
                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="example-fileinput" class="form-label">Priorité</label>
                                    <select name="cas_status" id="cas_status" class="form-control">
                                        <option value="">Tous</option>
                                        <option value="1">Urgent</option>
                                        <option value="2">Retard</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <table id="datatable11" class="dt-responsive nowrap w-100 table">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Date</th>
                                    <th>Code</th>
                                    <th>Patient</th>
                                    <th>Examens</th>
                                    <th>Contrat</th>
                                    <th>Compte rendu</th>
                                    <th>Urgent</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>


































































            <div class="card mb-md-0 mb-3" style="margin-top:20px;" id="casterminer">
                <div class="card-body">
                    <div class="card-widgets">
                        <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                        <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                            aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                        <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                    </div>
                    <h5 class="card-title mb-0">Liste des demandes terminées</h5>

                    <div id="cardCollpase1" class="show collapse pt-3">

                        <div class="row mb-3">

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="example-fileinput" class="form-label">Type d'examen</label>
                                    <select name="type_examen2" id="type_examen2" class="form-control">
                                        <option value="">Tous</option>
                                        @forelse ($types_orders as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->title }}
                                        </option>
                                        @empty
                                        Ajouter un Type d'examen
                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="example-fileinput" class="form-label">Status</label>
                                    <select name="exams_status2" id="exams_status2" class="form-control">
                                        <option value="">Tous</option>
                                        <option value="1">Valider</option>
                                        <option value="0">En attente</option>
                                        <option value="livrer">Livrer</option>
                                        <option value="non_livrer">Non Livrer</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="example-fileinput" class="form-label">Date début</label>
                                    <input type="date" name="dateBegin2" id="dateBegin2" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="example-fileinput" class="form-label">Date fin</label>
                                    <input type="date" name="dateEnd2" id="dateEnd2" class="form-control">
                                </div>
                            </div>
                        </div>

                        <table id="datatable33" class="dt-responsive nowrap w-100 table">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Date</th>
                                    <th>Code</th>
                                    <th>Patient</th>
                                    <th>Examens</th>
                                    <th>Contrat</th>
                                    <th>Compte rendu</th>
                                    <th>Urgent</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @endsection




    @push('extra-js')
    <script>
        var baseUrl = "{{ url('/') }}"
            var ROUTETESTORDERDATATABLE222 = "{{ route('test_order.getTestOrdersforDatatableMySpace') }}"
            var ROUTETESTORDERDATATABLE333 = "{{ route('test_order.getTestOrdersforDatatableMySpace2') }}"
            // var ROUTETESTORDERDATATABLE2 = "{{ route('test_order.getTestOrdersforDatatable2') }}"
            // var URLupdateAttribuate = "{{ url('attribuateDoctor') }}" + '/' + doctor_id + '/' + order_id
            // var URLUPDATEDELIVER = "{{ route('report.updateDeliver',"+id+") }}"
    </script>
    <script src="{{ asset('viewjs/test/order/index.js') }}"></script>


    <script src="{{ asset('viewjs/home.js') }}"></script>
    <script src="{{asset('adminassets/js/vendor/apexcharts.min.js')}}"></script>
    <script src="{{asset('adminassets/js/vendor/jquery-jvectormap-1.2.2.min.js')}}"></script>
    <script src="{{asset('adminassets/js/vendor/jquery-jvectormap-world-mill-en.js')}}"></script>
    <script src="{{asset('adminassets/js/vendor/Chart.bundle.min.js')}}"></script>
    <script src="{{asset('adminassets/js/pages/demo.dashboard-projects.js')}}"></script>
    <script src="{{asset('adminassets/js/pages/demo.dashboard.js')}}"></script>
    @endpush




    