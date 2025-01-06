@extends('layouts.app2')

@section('title', 'Employés')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#bs-example-modal-lg-create">Ajouter un nouveau employé</button>
            </div>
            <h4 class="page-title">Employés</h4>
        </div>
        @include('employees.create')
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
            <h5 class="card-title mb-0">Liste des employés</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom & Prénoms</th>
                            <th>Contacts</th>
                            <th>Actions</th>
                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($employees as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->first_name }} {{ $item->last_name }}</td>

                            <td>
                                <p>
                                    {{ $item->telephone }}
                                </p>
                                <p>
                                    {{ $item->email }}
                                </p>
                            </td>

                            <td>
                                <a href="{{ route('employee.detail', $item->id) }}" class="btn btn-primary"><i
                                        class="mdi mdi-eye"></i></a>

                                <button type="button" onclick="deleteModalEmployee({{ $item->id }})"
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