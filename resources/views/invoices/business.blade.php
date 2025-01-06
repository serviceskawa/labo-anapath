@extends('layouts.app2')

@section('title', 'Factures')

@section('content')

    <div class="">

        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <a href="{{ route('invoice.index') }}"><button type="button" class="btn btn-primary">Retour à la listes des
                        factures</button></a>
            </div>
            <h4 class="page-title">Factures</h4>
        </div>

        <!----MODAL---->
        @include('layouts.alerts')

        <div class="card mb-md-0 mb-3">
            <div class="card-body">
                <div class="card-widgets">
                    <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                    <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                        aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                    <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                </div>
                <h5 class="card-title mb-0">Liste des Factures </h5>

                <div id="cardCollpase1" class="collapse pt-3 show">

                    <div class="col-lg-4">

                    </div> <!-- end col -->
                    <table id="datatable1" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Mois</th>
                                <th>Facturés</th>
                                <th>Avoirs</th>
                                <th>Chiffre d'affaires</th>
                                <th>Encaissements</th>

                            </tr>
                        </thead>

                    </table>

                </div>
            </div>
        </div> <!-- end card-->

        <div class="card mb-md-0 mt-3">
            <div class="card-body">
                <div class="card-widgets">
                    <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                    <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                        aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                    <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                </div>
                <h5 class="card-title mb-0">Recherche par date </h5>

                <form method="POST" id="addDetailForm" autocomplete="off">
                    @csrf
                    <div class="row d-flex align-items-end mt-3">
                        <div class="col-md-4 col-12">
                            <label for="example-date" class="form-label">Date Début</label>
                            <input class="form-control" required id="starting_date" type="date" name="date">
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="example-date" class="form-label">Date Fin</label>
                            <input class="form-control" required id="ending_date" type="date" name="date">
                        </div>


                        <div class="col-md-2 col-12">
                            <div class="">
                                <button type="submit" class="btn btn-primary" id="add_detail">Afficher</button>
                            </div>
                        </div>
                    </div>

                </form>

                <div id="searchResponse" style="display: none">
                    <div id="cardCollpase1" class="show collapse pt-3">
                        <div class="row">
                            <div class="col-xxl-3 col-lg-6">
                                <div class="card widget-flat">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <i class="mdi mdi-currency-btc widget-icon bg-danger rounded-circle text-white"></i>
                                        </div>
                                        <h5 class="text-muted fw-normal mt-0" title="Revenue">Factures</h5>
                                        <h3 class="mt-3 mb-3" id="facture"></h3>
                                    </div>
                                </div>
                            </div> <!-- end col-->

                            <div class="col-xxl-3 col-lg-6">
                                <div class="card widget-flat bg-success text-white">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <i class="mdi mdi-account-multiple widget-icon bg-white text-success"></i>
                                        </div>
                                        <h6 class="text-uppercase mt-0" title="Customers">Chiffres d'affaire</h6>
                                        <h3 class="mt-3 mb-3" id="ca"></h3>
                                    </div>
                                </div>
                            </div> <!-- end col-->

                            <div class="col-xxl-3 col-lg-6">
                                <div class="card widget-flat bg-danger text-white">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <i class="mdi mdi-currency-usd widget-icon bg-light-lighten rounded-circle text-white"></i>
                                        </div>
                                        <h5 class="fw-normal mt-0" title="Revenue">Avoir</h5>
                                        <h3 class="mt-3 mb-3 text-white" id="avoir"></h3>
                                    </div>
                                </div>
                            </div> <!-- end col-->
                            <div class="col-xxl-3 col-lg-6">
                                <div class="card widget-flat bg-primary text-white">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <i class="mdi mdi-currency-usd widget-icon bg-light-lighten rounded-circle text-white"></i>
                                        </div>
                                        <h5 class="fw-normal mt-0" title="Revenue">Encaissement</h5>
                                        <h3 class="mt-3 mb-3 text-white" id="encaissement"></h3>
                                    </div>
                                </div>
                            </div> <!-- end col-->
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- end card-->


    </div>
@endsection


@push('extra-js')
    <script>
        var baseUrl = "{{url('/')}}"
        var ROUTEGETDATATABLE = "{{ route('invoice.getTestOrdersforDatatable') }}"
        var TOKENSEARCHINVOICE = "{{ csrf_token() }}"
        var ROUTESEARCHINVOICE = "{{ route('invoice.business.search') }}"
    </script>

    <script src="{{asset('viewjs/invoice/business.js')}}"></script>
@endpush
