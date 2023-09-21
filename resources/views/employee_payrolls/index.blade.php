@extends('layouts.app2')

@section('title', 'Paies|Employés')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#bs-example-modal-lg-create">Ajouter une paie</button>
            </div>
            <h4 class="page-title">Paies/Employés</h4>
        </div>
        @include('employee_payrolls.create')
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
            <h5 class="card-title mb-0">Liste de la paie des employés</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Actions</th>
                            <th>#</th>
                            <th>Nom du contrat</th>
                            <th>Salire brute/Mois</th>
                            <th>Taux brute/Heures</th>
                            <th>Indemnite de transport</th>
                            <th>Iban</th>
                            <th>Bic</th>
                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($payrolls as $item)
                        <tr>
                            <td>
                                @include('employee_payrolls.show',['item' => $item])
                                <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-show-{{ $item->id }}"
                                    class="btn btn-primary"><i class="mdi mdi-eye"></i> </button>

                                @include('employee_payrolls.edit',['item' => $item])
                                <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-edit-{{ $item->id }}" class="btn btn-info"><i
                                        class="mdi mdi-lead-pencil"></i>
                                </button>

                                <button type="button" onclick="deleteModalPayroll({{ $item->id }})"
                                    class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ App\Models\EmployeeContrat::find($item->contrat_employee_id)->contract_type }}
                            </td>
                            {{-- <td>{{ $item->employee_payroll->contract_type }}</td> --}}
                            <td>{{ $item->monthly_gross_salary }}</td>
                            <td>{{ $item->hourly_gross_rate }}</td>
                            <td>{{ $item->transport_allowance }}</td>
                            <td>{{ $item->iban }}</td>
                            <td>{{ $item->bic }}</td>
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