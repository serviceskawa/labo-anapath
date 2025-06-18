@extends('layouts.app2')

@section('title', 'Reports')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Recherche générale</h4>
            </div>
        </div>
    </div>

    <div class="">

        @include('layouts.alerts')

        <div class="card mb-md-0 mb-3" id="demandes">
            <div class="card-body">
                <div class="card-widgets">
                    <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                    <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                        aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                    <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                </div>


                <div class="row d-flex">
                    <div class="col-lg-3 p-1 ml-3 alert alert-success rounded-pill"
                        style="margin-right: 5px; text-align:center;">
                        <a href="#" style="text-decoration : none; color:inherit;">Champs de filtre</a>
                    </div>
                </div>

                <div id="cardCollpase1" class="show collapse pt-3">

                    

                    <div class="row mb-3">
                        <div class="col-lg-3 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Type d'examen</label>
                            <select class="form-select select2 filter-field" data-toggle="select2" id="type_examen_ids"
                                name="type_examen_ids[]" multiple>
                                <option disabled>Sélectionner le type d'examen</option>
                                @forelse ($types_orders as $type)
                                    <option value="{{ $type->id }}">{{ $type->title }}</option>
                                @empty
                                    <option disabled>Aucun type d'examen disponible</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Contrat</label>
                            <select class="form-select select2 filter-field" data-toggle="select2" name="contrat_ids[]"
                                id="contrat_ids" multiple>
                                <option disabled>Sélectionner le contrat</option>
                                @forelse ($contrats as $contrat)
                                    <option value="{{ $contrat->id }}">{{ $contrat->name }}</option>
                                @empty
                                    Aucun contrat disponible
                                @endforelse
                            </select>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Patient</label>
                            <select class="form-select select2 filter-field" data-toggle="select2" name="patient_ids[]"
                                id="patient_ids" multiple>
                                <option disabled>Sélectionner le nom du patient</option>
                                @forelse ($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->code }} - {{ $patient->firstname }}
                                    </option>
                                @empty
                                    Aucun patient disponible
                                @endforelse
                            </select>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label for="exampleFormControlInput3" class="form-label">Médecin traitant</label>
                            <select class="form-select select2 filter-field" data-toggle="select2" name="doctor_ids[]"
                                id="doctor_ids" multiple>
                                <option disabled>Sélectionner le médecin traitant</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Hôpital de provenance</label>
                            <select class="form-select select2 filter-field" data-toggle="select2" name="hospital_ids[]"
                                id="hospital_ids" multiple>
                                <option>Sélectionner le centre hospitalier de provenance</option>
                                @foreach ($hopitals as $hopital)
                                    <option value="{{ $hopital->id }}">{{ $hopital->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Référence hôpital</label>
                                <input type="text" class="form-control filter-field" name="reference_hopital"
                                    id="reference_hopital" placeholder="">
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label for="example-fileinput" class="form-label">Date début</label>
                            <input type="date" name="dateBegin" id="dateBegin" class="form-control">
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label for="example-fileinput" class="form-label">Date fin</label>
                            <input type="date" name="dateEnd" id="dateEnd" class="form-control filter-field">
                        </div>

                        <div class="col-lg-3 mb-3">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Recherche générale</label>
                                <input type="text" class="form-control filter-field" name="content" id="content"
                                    placeholder="">
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label for="example-fileinput" class="form-label">Urgent</label>
                            <select name="status_urgence_test_order_id" id="status_urgence_test_order_id"
                                class="form-control filter-field">
                                <option value="">Tous</option>
                                <option value="1">Urgent</option>
                                <option value="0">Retard</option>
                            </select>
                        </div>

                        <div class="col-lg-3 mb-3">
                            {{-- <button type="button" id="btn-export" class="btn btn-success">Exporter Excel</button> --}}
                            <a href="#" id="exportBtn" class="btn btn-success">Exporter Excel</a>

                        </div>
                    </div>


<form id="exportForm" method="GET" action="{{ route('reports.export') }}" target="_blank">
    <input type="hidden" name="type_examen_ids[]" id="export_type_examen_ids">
    <input type="hidden" name="contrat_ids[]" id="export_contrat_ids">
    <input type="hidden" name="patient_ids[]" id="export_patient_ids">
    <input type="hidden" name="doctor_ids[]" id="export_doctor_ids">
    <input type="hidden" name="hospital_ids[]" id="export_hospital_ids">
    <input type="hidden" name="reference_hopital" id="export_reference_hopital">
    <input type="hidden" name="dateBegin" id="export_dateBegin">
    <input type="hidden" name="dateEnd" id="export_dateEnd">
    <input type="hidden" name="content" id="export_content">
    <input type="hidden" name="status_urgence_test_order_id" id="export_status_urgence_test_order_id">
</form>




                    <table id="datatables-global-searchs" class="dt-responsive nowrap w-100 table">
                        <thead>
                            <tr>
                                <th>Code report</th>
                                <th>Code examen</th>
                                <th>Type d'examen</th>
                                <th>Contrat</th>
                                <th>Patient</th>
                                <th>Docteur</th>
                                <th>Hopital</th>
                                <th>Référence hopital</th>
                                <th>Date</th>
                                <th>Urgent</th>
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
        var baseUrl = "{{ url('/') }}"
        var ROUTEGETDATATABLE = "{{ route('search.getDataforDatatableSearchGlobal') }}"
        var TOKENSTOREDOCTOR = "{{ csrf_token() }}"
    </script>
    <script src="{{ asset('viewjs/search/search.js') }}"></script>
@endpush
