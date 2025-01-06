@extends('layouts.app2')

@section('title', 'Employés')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#bs-example-modal-lg-create">Ajouter un contrat pour un employé</button>
            </div>
            <h4 class="page-title">Contrat/Employés</h4>
        </div>
        @include('employee_contrats.create')
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
            <h5 class="card-title mb-0">Liste des contrats pour les employés</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom & Prénoms</th>
                            <th>Type contrat</th>
                            <th>Debut</th>
                            <th>Fin</th>
                            <th>Heure/Semaine</th>
                            <th>Jour/Semaine</th>
                            <th>Actions</th>
                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($employes_contrats as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->employee->first_name }} {{ $item->employee->last_name }}</td>

                            <td>{{ $item->contract_type }}</td>
                            <td>{{ $item->start_date }}</td>
                            <td>{{ $item->end_date }}</td>
                            <td>{{ $item->weekly_work_hours }}</td>
                            <td>{{ $item->working_days_per_week }}</td>
                            <td>
                                @include('employee_contrats.show',['item' => $item])
                                <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-show-{{ $item->id }}"
                                    class="btn btn-primary"><i class="mdi mdi-eye"></i> </button>

                                @include('employee_contrats.edit',['item' => $item])
                                <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-edit-{{ $item->id }}" class="btn btn-info"><i
                                        class="mdi mdi-lead-pencil"></i>
                                </button>

                                <button type="button" onclick="deleteModalEmployeeContrat({{ $item->id }})"
                                    class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


@push('extra-js')
<script>
    var baseUrl = "{{url('/')}}"
</script>
<script src="{{asset('viewjs/patient/index.js')}}"></script>
@endpush