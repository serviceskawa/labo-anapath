@extends('layouts.app2')

@section('title', 'Contrats')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">

            </div>
            <h4 class="page-title">Contrats</h4>
        </div>

    </div>
</div>


<div class="">


    @include('layouts.alerts')



    <div class="card mb-md-0 mb-3">
        <div class="card-body">
            <form action="{{ route('employee.contrat.update',$employeeContrat->id) }}" method="POST" autocomplete="on"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div id="progressbarwizard">
                    <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
                        <li class="nav-item">
                            <a href="#account-3" data-bs-toggle="tab" data-toggle="tab"
                                class="nav-link rounded-0 pt-2 pb-2">
                                <i class="mdi mdi-account-circle me-1"></i>
                                <span class="d-none d-sm-inline">Contrat</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#profile-tab-3" data-bs-toggle="tab" data-toggle="tab"
                                class="nav-link rounded-0 pt-2 pb-2">
                                <i class="mdi mdi-face-profile me-1"></i>
                                <span class="d-none d-sm-inline">Paie</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content b-0 mb-0">

                        <div id="bar" class="progress mb-3" style="height: 7px;">
                            <div class="bar progress-bar progress-bar-striped progress-bar-animated bg-success">
                            </div>
                        </div>

                        {{-- Section1 --}}
                        <div class="tab-pane" id="account-3">
                            <input type="hidden" name="employee_id"
                                value="{{ $employeeContrat->employee_contrat->employee_id }}">

                            <div class="row">
                                <div class="mb-3 col-lg-12">
                                    <label for="example-select" class="form-label">Type de
                                        contrat<span style="color:red;">*</span></label>
                                    <select class="form-select" name="contract_type" required>
                                        <option>Selectionner un type de contrat</option>
                                        <option value="CDI" {{$employeeContrat->employee_contrat->contract_type=="CDI" ?
                                            'selected' : ''}}>CDI</option>
                                        <option value="CDD" {{$employeeContrat->employee_contrat->contract_type=="CDD" ?
                                            'selected' : ''}}>CDD</option>
                                        <option value="Saisonnier" {{$employeeContrat->
                                            employee_contrat->contract_type=="Saisonnier" ? 'selected' : ''}}>Saisonnier
                                        </option>
                                        <option value="Apprentissage" {{$employeeContrat->
                                            employee_contrat->contract_type=="Apprentissage" ? 'selected' :
                                            ''}}>Apprentissage</option>
                                        <option value="Extra" {{$employeeContrat->
                                            employee_contrat->contract_type=="Extra" ? 'selected' : ''}}>Extra</option>
                                        <option value="Intérim" {{$employeeContrat->
                                            employee_contrat->contract_type=="Intérim" ? 'selected' : ''}}>Intérim
                                        </option>
                                        <option value="Stagiaire" {{$employeeContrat->
                                            employee_contrat->contract_type=="Stagiaire" ? 'selected' : ''}}>Stagiaire
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-lg-6">
                                    <label for="simpleinput" class="form-label">Date de début<span
                                            style="color:red;">*</span></label>
                                    <input type="date" name="start_date"
                                        value="{{ old('start_date') ? old('start_date') : $employeeContrat->employee_contrat->start_date }}"
                                        class="form-control">
                                </div>

                                <div class="mb-3 col-lg-6">
                                    <label for="simpleinput" class="form-label">Date de fin</label>
                                    <input type="date"
                                        value="{{ old('end_date') ? old('end_date') : $employeeContrat->employee_contrat->end_date }}"
                                        name="end_date" class="form-control" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-lg-12">
                                    <label for="simpleinput" class="form-label">Date
                                        d'évaluation</label>
                                    <input type="date"
                                        value="{{ old('probation_end_date') ? old('probation_end_date') : $employeeContrat->employee_contrat->probation_end_date }}"
                                        name="probation_end_date" class="form-control" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-lg-6">
                                    <label for="simpleinput" class="form-label">Heure/Semaine</label>
                                    <input type="number"
                                        value="{{ old('weekly_work_hours') ? old('weekly_work_hours') : $employeeContrat->employee_contrat->weekly_work_hours }}"
                                        name="weekly_work_hours" class="form-control" />
                                </div>

                                <div class="mb-3 col-lg-6">
                                    <label for="simpleinput" class="form-label">Jour/Semaine</label>
                                    <input type="number"
                                        value="{{ old('working_days_per_week') ? old('working_days_per_week') : $employeeContrat->employee_contrat->working_days_per_week }}"
                                        name="working_days_per_week" class="form-control" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-lg-12">
                                    <label for="simpleinput" class="form-label">Raison du fin de
                                        contrat</label>
                                    <textarea type="text" name="termination_reason"
                                        class="form-control">{{ old('termination_reason') ? old('termination_reason') : $employeeContrat->employee_contrat->termination_reason }}</textarea>
                                </div>
                            </div>
                        </div>














                        {{-- SECTION 2 --}}
                        <div class="tab-pane" id="profile-tab-3">
                            <div class="row">
                                <div class="mb-3 col-lg-6">
                                    <label for="simpleinput" class="form-label">Salaire
                                        brute/Mois</label>
                                    <input type="number"
                                        value="{{ old('monthly_gross_salary') ? old('monthly_gross_salary') : $employeeContrat->monthly_gross_salary }}"
                                        name="monthly_gross_salary" class="form-control">
                                </div>

                                <div class="mb-3 col-lg-6">
                                    <label for="simpleinput" class="form-label">Taux
                                        brute/Heures</label>
                                    <input type="number"
                                        value="{{ old('hourly_gross_rate') ? old('hourly_gross_rate') : $employeeContrat->hourly_gross_rate }}"
                                        name="hourly_gross_rate" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-lg-6">
                                    <label for="simpleinput" class="form-label">Indemnite de
                                        transport</label>
                                    <input type="number"
                                        value="{{ old('transport_allowance') ? old('transport_allowance') : $employeeContrat->transport_allowance }}"
                                        name="transport_allowance" class="form-control" />
                                </div>

                                <div class="mb-3 col-lg-6">
                                    <label for="simpleinput" class="form-label">Iban</label>
                                    <input type="text" value="{{ old('iban') ? old('iban') : $employeeContrat->iban }}"
                                        name="iban" class="form-control" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-lg-6">
                                    <label for="simpleinput" class="form-label">Bic</label>
                                    <input type="text" value="{{ old('bic') ? old('bic') : $employeeContrat->bic }}"
                                        name="bic" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <ul class="list-inline mb-0 wizard">
                            <li class="next list-inline-item float-end">
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </form>

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