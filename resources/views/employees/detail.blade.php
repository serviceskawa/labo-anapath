@extends('layouts.app2')

@section('title', 'Profile')

@section('content')

@include('layouts.alerts')

{{-- Profil --}}

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <a href="{{ route('employee.index') }}" type="button" class="btn btn-primary"> <i
                        class="dripicons-reply"></i>
                    Retour</a>
            </div>
            <h4 class="page-title">Employé</h4>
        </div>
    </div>
</div>

{{-- Creer un employer --}}
<div class="">
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-primary">
                <div class="card-body profile-user-box">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    @if($employee->photo_url==null)
                                    <div class="avatar-lg">
                                        <div class="avatar-sm" style="margin-right: 10px">
                                            <span class="avatar-title bg-light rounded-circle me-2"
                                                style="padding: 50px; font-size:50px; color:inherit;">
                                                {{getNameInitials($employee->first_name." ".$employee->last_name." ")}}
                                            </span>
                                        </div>
                                    </div>
                                    @else
                                    <div class="avatar-lg">
                                        <img src="{{ asset('storage/' . $employee->photo_url) }}" alt=""
                                            class="rounded-circle img-thumbnail">
                                    </div>
                                    @endif
                                </div>
                                <div class="col">
                                    <div>
                                        <h3 class="mt-1 mb-1 text-white" style="font-weight: bold;">{{
                                            $employee->last_name }} {{
                                            $employee->first_name }}
                                        </h3>
                                        <p class="font-13 text-white-50"><i class="uil-phone-alt"
                                                style="font-size: 17px;"></i> &nbsp;{{
                                            $employee->telephone }} &nbsp;&nbsp;&nbsp; <i class="uil-envelope-alt"
                                                style="font-size: 17px;"></i> &nbsp;{{
                                            $employee->email }}</p>

                                        <ul class="mb-0 list-inline text-light">
                                            <li class="list-inline-item me-3">
                                                <h5 class="mb-1">
                                                    @if($employee->address==null)
                                                    Non renseigné
                                                    @else
                                                    {{ $employee->address
                                                    }}
                                                    @endif
                                                </h5>
                                                <p class="mb-0 font-13 text-white-50">
                                                    Adresse
                                                </p>
                                            </li>
                                            <li class="list-inline-item me-3">
                                                <h5 class="mb-1">
                                                    @if($employee->date_of_birth==null)
                                                    Non renseigné
                                                    @else
                                                    {{ $employee->date_of_birth
                                                    }}
                                                    @endif
                                                    ,&nbsp;
                                                    @if($employee->place_of_birth==null)
                                                    Non renseigné
                                                    @else
                                                    {{ $employee->place_of_birth }}
                                                    @endif
                                                </h5>
                                                <p class="mb-0 font-13 text-white-50">
                                                    Date et lieu de naissance
                                                </p>
                                            </li>
                                            <li class="list-inline-item me-3">
                                                <h5 class="mb-1">
                                                    @if($employee->cnss_number==null)
                                                    Non renseigné
                                                    @else
                                                    {{ $employee->cnss_number }}
                                                    @endif
                                                </h5>
                                                <p class="mb-0 font-13 text-white-50">
                                                    N° de sécurité sociale
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            @include('employees.edit',['item' => $employee])
                            <div class="text-center mt-sm-0 mt-3 text-sm-end">
                                <button type="button" class="btn btn-light" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-edit-{{ $employee->id }}">
                                    Modifier
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>



{{-- Contrats --}}
<div class="">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body profile-user-box">
                    <div class="row d-flex">
                        <div class="page-title-box">
                            <div class="page-title-right mr-3">
                                @include('employee_contrats.create')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-contrat-create"><i class="uil-plus"></i>Nouveau
                                    contrat
                                </button>
                            </div>
                            <h4 class="page-title">Contrats</h4>
                        </div>
                    </div>

                    <div id="cardCollpase1" class="collapse pt-3 show">

                        <table id="datatable2" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Début</th>
                                    <th>Fin</th>
                                    <th>Salaire brute mensuelle</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($paies as $paie)
                                @if($paie->employee_contrat->employee_id== $employee->id)
                                <tr>
                                    <td>{{ $paie->id }}</td>
                                    <td>{{ $paie->employee_contrat->contract_type }}</td>
                                    <td>{{ $paie->employee_contrat->start_date }}</td>
                                    <td>
                                        @if($paie->employee_contrat->end_date==null)
                                        Non renseigné
                                        @else
                                        {{ $paie->employee_contrat->end_date}}
                                        @endif
                                    </td>

                                    <td>
                                        @if($paie->monthly_gross_salary==null)
                                        Non renseigné
                                        @else
                                        {{ $paie->monthly_gross_salary}}
                                        @endif
                                    </td>
                                    <td>


                                        <a href="{{ route('employee.contrat.edit',$paie->id) }}" class="btn btn-info"><i
                                                class="mdi mdi-lead-pencil"></i></a>

                                        <button type="button" onclick="deleteModalEmployeeContrat({{ $paie->id }})"
                                            class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



{{-- Congés --}}
<div class="">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body profile-user-box">
                    {{-- <h4 class="header-title mt-0 mb-3">Congés</h4> --}}
                    <div class="row">
                        <div class="page-title-box">
                            <div class="page-title-right mr-3">
                                @include('employee_timeoffs.create')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-timeoffs-create">
                                    <i class="uil-plus me-1"></i>Demande de congé
                                </button>
                            </div>
                            <h4 class="page-title">Congés</h4>
                        </div>
                        {{-- <div class="col-sm-8">
                        </div>
                        <div class="col-sm-4">
                            @include('employee_timeoffs.create')
                            <div class="text-center mt-sm-0 text-sm-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-timeoffs-create">
                                    <i class="uil-plus me-1"></i>Demande de congé
                                </button>
                            </div>
                        </div> --}}
                    </div>

                    <div id="cardCollpase1" class="collapse pt-3 show">

                        <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Date début</th>
                                    <th>Date fin</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($conges as $conge)
                                @if($conge->employee_id==$employee->id)
                                <tr>
                                    <td>{{ $conge->start_date }}</td>
                                    <td>{{ $conge->end_date }}</td>
                                    <td>{{ $conge->timeoff_type }}</td>
                                    <td>{{ $conge->status }}</td>
                                    <td>
                                        @include('employee_timeoffs.edit',['item' => $conge])
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#bs-example-modal-lg-edit-{{ $conge->id }}"
                                            class="btn btn-info"><i class="mdi mdi-lead-pencil"></i>
                                        </button>

                                        <button type="button" onclick="deleteModalTimeoff({{ $conge->id }})"
                                            class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



{{-- Documents --}}
<div class="">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body profile-user-box">
                    <div class="row">
                        <div class="page-title-box">
                            <div class="page-title-right mr-3">
                                @include('employees.documents.create')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-file-create">
                                    <i class="uil-plus me-1">Nouveau document</i>
                                </button>
                            </div>
                            <h4 class="page-title">Documents</h4>
                        </div>
                    </div>

                    <div id="cardCollpase1" class="collapse pt-3 show">

                        <table id="datatable3" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Fichier</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($documents as $document)

                                <tr>
                                    @if($document->employee_id==$employee->id)
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $document->name_file }}</td>
                                    <td><a href="{{ asset('storage/' . $document->file)  }}" download>Télécharger</a>
                                    </td>
                                    <td>
                                        @include('employees.documents.edit',['document' => $document])
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#bs-example-modal-lg-file-edit-{{ $document->id }}"
                                            class="btn btn-info"><i class="mdi mdi-lead-pencil"></i>
                                        </button>

                                        <button type="button" onclick="deleteModalDocument({{ $document->id }})"
                                            class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                                    </td>
                                    @endif
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
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