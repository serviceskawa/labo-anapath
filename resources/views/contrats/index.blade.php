@extends('layouts.app2')

@section('title', 'Contrats')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#standard-modal">Ajouter un nouveau contrat</button>
            </div>
            <h4 class="page-title">Contrats</h4>
        </div>

        <!----MODAL---->

        @include('contrats.create')

        @include('contrats.edit')

    </div>
</div>


<div class="">


    @include('layouts.alerts')



    <div class="card mb-md-0 mb-3">
        <div class="card-body">
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                    aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            <h5 class="card-title mb-0">Liste des contrats</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">

                <div class="row mb-3">

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Rechercher</label>
                            <input type="text" name="contenu" id="contenu" class="form-control"
                                placeholder="Contrat ou client professionnel">
                        </div>
                    </div>


                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Status</label>
                            <select name="statusquery" id="statusquery" class="form-control">
                                <option value="">Tous</option>
                                <option value="ACTIF">ACTIF</option>
                                <option value="INACTIF">INACTIF</option>
                                <option value="1">CLÔTURER</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Status facture</label>
                            <select name="cas_status" id="cas_status" class="form-control">
                                <option value="">Tous</option>
                                <option value="0">En attente</option>
                                <option value="1">Payé</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Date Début</label>
                            <input type="date" name="dateBegin" id="dateBegin" class="form-control">
                        </div>
                    </div> <!-- end col -->

                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Date fin</label>
                            <input type="date" name="dateEnd" id="dateEnd" class="form-control">
                        </div>
                    </div> <!-- end col -->

                </div>




                <table id="datatable2" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Contrat</th>
                            <th>Nombre d’examens</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>


                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>


</div>
@endsection


@push('extra-js')

<script>
    var baseUrl = "{{ url('/') }}";
    var ROUTEGETDATATABLE = "{{ route('contrat.getContratsforDatatable') }}"
</script>

<script src="{{asset('viewjs/contrat/index.js')}}"></script>
@endpush
