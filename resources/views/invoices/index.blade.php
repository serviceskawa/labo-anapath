@extends('layouts.app2')

@section('title', 'Factures')

@section('content')

<div class="">

    <div class="page-title-box">
        <div class="page-title-right mr-3">
            <a href="{{ route('invoice.create') }}"><button type="button" class="btn btn-primary">Ajouter une
                    nouvelle facture</button></a>
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

                <div class="row mb-3">

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Rechercher</label>
                            <input type="text" placeholder="Par code d'examen" name="contenu" id="contenu" class="form-control">
                        </div>
                    </div> <!-- end col -->

                    <div class="col-lg-3">

                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Status</label>
                            <select name="cas_status" id="cas_status" class="form-control">
                                <option value="">Tous</option>
                                <option value="0">En attente</option>
                                <option value="1">Payé</option>
                            </select>
                        </div>

                    </div> <!-- end col -->

                    <div class="col-lg-3">

                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Type de facture</label>
                            <select name="status_invoice" id="status_invoice" class="form-control">
                                <option value="">Tous</option>
                                <option value="0">Facture de vente</option>
                                <option value="1">Facture d'avoir</option>
                            </select>
                        </div>

                    </div> <!-- end col -->

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Date Début</label>
                            <input type="date" name="dateBegin" id="dateBegin" class="form-control">
                        </div>
                    </div> <!-- end col -->

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Date fin</label>
                            <input type="date" name="dateEnd" id="dateEnd" class="form-control">
                        </div>
                    </div> <!-- end col -->
                </div>


                <table id="datatable1" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            {{-- <th>#</th> --}}
                            <th>Date</th>
                            <th>Code de la facture</th>
                            <th>Demande</th>
                            <th>Patient</th>
                            <th>Total</th>
                            <th>Contrat</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Actions</th>

                        </tr>
                    </thead>

                </table>

            </div>
        </div>
    </div> <!-- end card-->


</div>
@endsection

@push('extra-js')
    <script>
        var baseUrl = "{{url('/')}}"
        var ROUTEGETDATATABLE = "{{ route('invoice.getInvoiceIndexforDatatable') }}"
    </script>

    <script src="{{asset('viewjs/invoice/index.js')}}"></script>
@endpush
