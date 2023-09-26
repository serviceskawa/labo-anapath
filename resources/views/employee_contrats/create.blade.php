<!-- Large modal -->
<div class="modal fade" id="bs-example-modal-lg-contrat-create" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Contrat de {{
                    $employee->last_name }} {{ $employee->first_name }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">

                                {{-- <h4 class="header-title mb-3">Wizard With Progress Bar</h4> --}}

                                <form action="{{ route('employee.contrat.store') }}" method="POST" autocomplete="on"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div id="progressbarwizard">
                                        <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
                                            <li class="nav-item">
                                                <a href="#account-2" data-bs-toggle="tab" data-toggle="tab"
                                                    class="nav-link rounded-0 pt-2 pb-2">
                                                    <i class="mdi mdi-account-circle me-1"></i>
                                                    <span class="d-none d-sm-inline">Contrat</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#profile-tab-2" data-bs-toggle="tab" data-toggle="tab"
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



                                            {{-- Section1 --}}
                                            <div class="tab-pane" id="account-2">
                                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                                                <div class="row">
                                                    <div class="mb-3 col-lg-12">
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

                                                <div class="row">
                                                    <div class="mb-3 col-lg-6">
                                                        <label for="simpleinput" class="form-label">Date de début<span
                                                                style="color:red;">*</span></label>
                                                        <input type="date" name="start_date" class="form-control">
                                                    </div>

                                                    <div class="mb-3 col-lg-6">
                                                        <label for="simpleinput" class="form-label">Date de fin</label>
                                                        <input type="date" name="end_date" class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="mb-3 col-lg-12">
                                                        <label for="simpleinput" class="form-label">Date
                                                            d'évaluation</label>
                                                        <input type="date" name="probation_end_date"
                                                            class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="mb-3 col-lg-6">
                                                        <label for="simpleinput"
                                                            class="form-label">Heure/Semaine</label>
                                                        <input type="number" name="weekly_work_hours"
                                                            class="form-control" />
                                                    </div>

                                                    <div class="mb-3 col-lg-6">
                                                        <label for="simpleinput" class="form-label">Jour/Semaine</label>
                                                        <input type="number" name="working_days_per_week"
                                                            class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="mb-3 col-lg-12">
                                                        <label for="simpleinput" class="form-label">Raison du fin de
                                                            contrat</label>
                                                        <textarea type="text" name="termination_reason"
                                                            class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>














                                            {{-- SECTION 2 --}}
                                            <div class="tab-pane" id="profile-tab-2">
                                                <div class="row">
                                                    <div class="mb-3 col-lg-6">
                                                        <label for="simpleinput" class="form-label">Salaire
                                                            brute/Mois</label>
                                                        <input type="number" name="monthly_gross_salary"
                                                            class="form-control">
                                                    </div>

                                                    <div class="mb-3 col-lg-6">
                                                        <label for="simpleinput" class="form-label">Taux
                                                            brute/Heures</label>
                                                        <input type="number" name="hourly_gross_rate"
                                                            class="form-control">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="mb-3 col-lg-6">
                                                        <label for="simpleinput" class="form-label">Indemnite de
                                                            transport</label>
                                                        <input type="number" name="transport_allowance"
                                                            class="form-control" />
                                                    </div>

                                                    <div class="mb-3 col-lg-6">
                                                        <label for="simpleinput" class="form-label">Numéro de compte bancaire (IBAN)</label>
                                                        <input type="text" name="iban" class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="mb-3 col-lg-6">
                                                        <label for="simpleinput" class="form-label">BIC</label>
                                                        <input type="text" name="bic" class="form-control" />
                                                    </div>
                                                </div>

                                                {{-- <div class="row text-align float-end">
                                                    <button type="submit" class="btn btn-primary">Ajouter un contrat
                                                        pour un employé</button>
                                                </div> --}}
                                            </div>

                                            <ul class="list-inline mb-0 wizard">
                                                {{-- <li class="previous list-inline-item">
                                                    <a href="#" class="btn btn-info">Précedent</a>
                                                </li> --}}
                                                <li class="next list-inline-item float-end">
                                                    <button type="submit" class="btn btn-primary">Ajouter un contrat
                                                        pour un employé</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <form action="{{ route('employee.contrat.store') }}" method="POST" autocomplete="on"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Type de contrat<span
                                        style="color:red;">*</span></label>
                                <input type="text" name="contract_type" class="form-control" required>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Date de début<span
                                        style="color:red;">*</span></label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Date de fin<span
                                        style="color:red;">*</span></label>
                                <input type="date" name="end_date" class="form-control" required />
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Date de fin de probation<span
                                        style="color:red;">*</span></label>
                                <input type="date" name="probation_end_date" class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Heure/Semaine<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="weekly_work_hours" class="form-control" required />
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Jour/Semaine<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="working_days_per_week" class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Raison du fin de contrat<span
                                        style="color:red;">*</span></label>
                                <textarea type="text" name="termination_reason" class="form-control"
                                    required></textarea>
                            </div>
                        </div>

                        <h3 style="text-align:center;">Paie</h3>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="example-select" class="form-label">Contrat employée<span
                                        style="color:red;">*</span></label>
                                <select class="form-select" name="contrat_employee_id">
                                    <option>Selectionner un contrat employée</option>
                                    <option value="CDI">CDI</option>
                                    <option value="CDD">CDD</option>
                                    <option value="Saisonnier">Saisonnier</option>
                                    <option value="Apprentissage">Apprentissage</option>
                                    <option value="Extra">Extra</option>
                                    <option value="Intérim">Intérim</option>
                                    <option value="Stagiaire">Stagiaire</option>
                                </select>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Salire brute/Mois<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="monthly_gross_salary" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Taux brute/Heures<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="hourly_gross_rate" class="form-control" required>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Indemnite de transport<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="transport_allowance" class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Iban<span
                                        style="color:red;">*</span></label>
                                <input type="text" name="iban" class="form-control" required />
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Bic<span style="color:red;">*</span></label>
                                <input type="text" name="bic" class="form-control" required />
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter un contrat pour un employé</button>
                    </div>
                </form> --}}
            </div>
        </div>
    </div>
</div>