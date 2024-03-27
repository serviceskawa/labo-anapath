@extends('layouts.app2')

@section('title', 'Reports')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Suivi des demandes</h4>

        </div>

        <!----MODAL---->

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
            <h5 class="card-title mb-0">Liste des demandes suivi</h5>

            <div id="cardCollpase1" class="show collapse pt-3">

                <div class="row mb-3">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Rechercher</label>
                            <input type="text" name="contenu" id="contenu" class="form-control">
                        </div>
                    </div>


                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Status</label>
                            <select name="statusquery" id="statusquery" class="form-control">
                                <option value="">Tous</option>
                                <option value="0">Attente</option>
                                <option value="1">Valider</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Date début</label>
                            <input type="date" name="dateBegin" id="dateBegin" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Date fin</label>
                            <input type="date" name="dateEnd" id="dateEnd" class="form-control">
                        </div>
                    </div>
                </div>



                <table id="datatablesuivi" class="table-striped dt-responsive nowrap w-100 table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Macro</th>
                            <th>Compte rendu</th>
                            <th>Patient informé</th>
                            <th>Patient livré</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('extra-js')
<script>
    var baseUrl = "{{url('/')}}"
       var ROUTEGETDATATABLE = "{{ route('report.getTestOrdersforDatatableSuivi') }}"
</script>
<script src="{{asset('viewjs/report/suivi.js')}}"></script>







@endpush