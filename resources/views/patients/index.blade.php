@extends('layouts.app2')

@section('title', 'Patient')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#standard-modal">Ajouter un nouveau patient</button>
                </div>
                <h4 class="page-title">Patients</h4>
            </div>

            @include('patients.create')

            @include('patients.edit')
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
                <h5 class="card-title mb-0">Liste des patients</h5>

                <div id="cardCollpase1" class="collapse pt-3 show">
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Rechercher</label>
                                <input type="text" name="contenu" id="contenu" class="form-control">
                            </div>
                        </div>
                    </div>

                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Nom & Prénoms</th>
                                <th>Contacts</th>
                                <th>Total</th>
                                <th>Paye</th>
                                <th>Due</th>
                                <th>Actions</th>

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
        var baseUrl = "{{ url('/') }}";
        var ROUTEGETDATATABLE = "{{ route('patient.getPatientsforDatatable') }}"
    </script>
    <script src="{{ asset('viewjs/patient/index.js') }}"></script>
@endpush
