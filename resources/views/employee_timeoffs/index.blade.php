@extends('layouts.app2')

@section('title', 'Employés')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#bs-example-modal-lg-create">Ajouter un congé pour un employé</button>
            </div>
            <h4 class="page-title">Congés</h4>
        </div>
        @include('employee_timeoffs.create')
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
            <h5 class="card-title mb-0">Liste des congés pour les employés</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Actions</th>
                            <th>#</th>
                            <th>Nom & Prénoms</th>
                            <th>Type congé</th>
                            <th>Debut</th>
                            <th>Fin</th>
                            <th>Message</th>
                            <th>Status</th>
                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($timeoffs as $item)
                        <tr>
                            <td>
                                @include('employee_timeoffs.show',['item' => $item])
                                <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-show-{{ $item->id }}"
                                    class="btn btn-primary"><i class="mdi mdi-eye"></i> </button>

                                @include('employee_timeoffs.edit',['item' => $item])
                                <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-edit-{{ $item->id }}" class="btn btn-info"><i
                                        class="mdi mdi-lead-pencil"></i>
                                </button>

                                <button type="button" onclick="deleteModalTimeoff({{ $item->id }})"
                                    class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->employee->first_name }} {{ $item->employee->last_name }}</td>
                            <td>{{ $item->timeoff_type }}</td>
                            <td>{{ $item->start_date }}</td>
                            <td>{{ $item->end_date }}</td>
                            <td>{{ $item->message }}</td>
                            <td>{{ $item->status }}</td>
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