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
                        {{-- <tbody>
                            @foreach ($patients as $item)
                                <tr>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->firstname }} {{ $item->lastname }}</td>
                                    <td>{{ $item->telephone1 . ' / ' . $item->telephone2 }}</td>
                                    <td>{{ getTotalByPatient($item->id) }}</td>
                                    <td>{{ getPaidByPatient($item->id) }}</td>
                                    <td>{{ getNoPaidByPatient($item->id) }}</td>
                                    <td>
                                        <button type="button" onclick="edit({{ $item->id }})"
                                            class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </button>
                                        <button type="button" onclick="deleteModal({{ $item->id }})"
                                            class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                                        <a type="button" href="{{ route('patients.profil', $item->id) }}"
                                            class="btn btn-secondary"><i class="mdi mdi-eye"></i> </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody> --}}
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
