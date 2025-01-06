<div class="modal fade" id="bs-example-modal-lg-contrat-edit-{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Contrat de {{ $employee->first_name }} {{
                    $employee->last_name }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>

            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('employee.contrat.update',$item->id) }}" method="POST" autocomplete="on"
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
                                        <div
                                            class="bar progress-bar progress-bar-striped progress-bar-animated bg-success">
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="account-3">
                                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                        tt
                                        <div class="row">
                                            <div class="mb-3">
                                                <label for="example-select" class="form-label">Type de
                                                    contrat<span style="color:red;">*</span></label>
                                                <select class="form-select" name="contract_type" required>
                                                    <option>Selectionner un type de contrat</option>
                                                    <option value="CDI">CDI</option>
                                                    <option value="CDD">CDD</option>
                                                    <option value="Saisonnier">Saisonnier</option>
                                                    <option value="Apprentissage">Apprentissage</option>
                                                    <option value="Extra">Extra</option>
                                                    <option value="Intérim">Intérim</option>
                                                    <option value="Stagiaire">Stagiaire</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- <div class="row">
                                            <div class="mb-3 col-lg-6">
                                                <label for="simpleinput" class="form-label">Date de début<span
                                                        style="color:red;">*</span></label>
                                                <input type="date" name="start_date"
                                                    value="{{ old('start_date') ? old('start_date') : $item->employee_contrat->start_date }}"
                                                    class="form-control">
                                            </div>

                                            <div class="mb-3 col-lg-6">
                                                <label for="simpleinput" class="form-label">Date de fin</label>
                                                <input type="date"
                                                    value="{{ old('end_date') ? old('end_date') : $item->employee_contrat->end_date }}"
                                                    name="end_date" class="form-control" />
                                            </div>
                                        </div> --}}

                                        {{-- <div class="row">
                                            <div class="mb-3 col-lg-12">
                                                <label for="simpleinput" class="form-label">Date
                                                    d'évaluation</label>
                                                <input type="date"
                                                    value="{{ old('probation_end_date') ? old('probation_end_date') : $item->employee_contrat->probation_end_date }}"
                                                    name="probation_end_date" class="form-control" />
                                            </div>
                                        </div> --}}

                                        {{-- <div class="row">
                                            <div class="mb-3 col-lg-6">
                                                <label for="simpleinput" class="form-label">Heure/Semaine</label>
                                                <input type="number"
                                                    value="{{ old('weekly_work_hours') ? old('weekly_work_hours') : $item->employee_contrat->weekly_work_hours }}"
                                                    name="weekly_work_hours" class="form-control" />
                                            </div>

                                            <div class="mb-3 col-lg-6">
                                                <label for="simpleinput" class="form-label">Jour/Semaine</label>
                                                <input type="number"
                                                    value="{{ old('working_days_per_week') ? old('working_days_per_week') : $item->employee_contrat->working_days_per_week }}"
                                                    name="working_days_per_week" class="form-control" />
                                            </div>
                                        </div> --}}

                                        {{-- <div class="row">
                                            <div class="mb-3 col-lg-12">
                                                <label for="simpleinput" class="form-label">Raison du fin de
                                                    contrat</label>
                                                <textarea type="text" name="termination_reason"
                                                    class="form-control">{{ old('termination_reason') ? old('termination_reason') : $item->employee_contrat->termination_reason }}</textarea>
                                            </div>
                                        </div> --}}
                                    </div>








                                    <div class="tab-pane" id="profile-tab-3">
                                        <div class="row">
                                            <div class="col-12">

                                            </div>
                                        </div>
                                    </div>


                                    <ul class="list-inline mb-0 wizard">
                                        <li class="next list-inline-item float-end">
                                            <a href="#" class="btn btn-info">Next</a>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>