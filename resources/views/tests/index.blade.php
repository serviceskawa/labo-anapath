@extends('layouts.app2')

@section('title', 'Examens')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#standard-modal">Ajouter un nouvel examen</button>
            </div>
            <h4 class="page-title">Examens</h4>
        </div>

        <!----MODAL---->

        @include('tests.create')

        @include('tests.edit')

    </div>
</div>


<div class="">


    @include('layouts.alerts')



    {{-- <div class="card mb-md-0 mb-3">
        <div class="card-body">
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                    aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            <h5 class="card-title mb-0">Liste des examens</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">

                <table id="datatable11" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Nom de l'examen</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    </tbody>
                </table>

            </div>
        </div>
    </div> --}}


    <div class="card mb-md-0 mb-3">
        <div class="card-body">
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                    aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            <h5 class="card-title mb-0">Liste des examens</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">

                <div class="row mb-3">

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Rechercher</label>
                            <input type="text" name="contenu" id="contenu" class="form-control">
                        </div>
                    </div>


                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Statut</label>
                            <select name="statusquery" id="statusquery" class="form-control">
                                <option value="">Tous</option>
                                <option value="ACTIF">ACTIF</option>
                                <option value="INACTIF">INACTIF</option>
                            </select>
                        </div>
                    </div>

                </div>




                <table id="datatable11" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Nom de l'examen</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Status</th>
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
    var baseUrl = "{{url('/')}}";
    var ROUTEGETDATATABLE2 = "{{ route('test.getTestsforDatatable') }}"
</script>

<script src="{{asset('viewjs/test/index.js')}}"></script>
@endpush