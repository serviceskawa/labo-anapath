<!-- Large modal -->
<div class="modal fade" id="bs-example-modal-lg-create" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Ajouter un contrat pour employé</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employee.contrat.store') }}" method="POST" autocomplete="on"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="example-select" class="form-label">Nom de l'employé<span
                                        style="color:red;">*</span></label>
                                <select class="form-select" name="employee_id">
                                    <option>Selectionner un employé</option>
                                    @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->fisrt_name }} {{ $emp->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Type de contrat<span
                                        style="color:red;">*</span></label>
                                <input type="text" name="contract_type" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Date de début<span
                                        style="color:red;">*</span></label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Date de fin<span
                                        style="color:red;">*</span></label>
                                <input type="date" name="end_date" class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Date de fin de probation<span
                                        style="color:red;">*</span></label>
                                <input type="date" name="probation_end_date" class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Heure/Semaine<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="weekly_work_hours" class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
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

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter un contrat pour un employé</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>