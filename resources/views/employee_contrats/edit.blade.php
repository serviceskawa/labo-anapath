<div class="modal fade" id="bs-example-modal-lg-edit-{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Modifier cet contrat</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employee.contrat.update',$item->id) }}" method="POST" autocomplete="on"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">

                        <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="example-select" class="form-label">Nom de l'employé<span
                                        style="color:red;">*</span></label>
                                <select class="form-select" name="employee_id">
                                    <option>Selectionner un employé</option>
                                    @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ $emp->id == $item->employee_id ? 'selected' :
                                        ''}}>{{
                                        $emp->fisrt_name }} {{ $emp->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Type de contrat<span
                                        style="color:red;">*</span></label>
                                <input type="text" name="contract_type"
                                    value="{{ old('contract_type') ? old('contract_type') : $item->contract_type }}"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Date de début<span
                                        style="color:red;">*</span></label>
                                <input type="date" name="start_date"
                                    value="{{ old('start_date') ? old('start_date') : $item->start_date }}"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Date de fin<span
                                        style="color:red;">*</span></label>
                                <input type="date" name="end_date"
                                    value="{{ old('end_date') ? old('end_date') : $item->end_date }}"
                                    class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Date de fin de probation<span
                                        style="color:red;">*</span></label>
                                <input type="date"
                                    value="{{ old('probation_end_date') ? old('probation_end_date') : $item->probation_end_date }}"
                                    name="probation_end_date" class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Heure/Semaine<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="weekly_work_hours"
                                    value="{{ old('weekly_work_hours') ? old('weekly_work_hours') : $item->weekly_work_hours }}"
                                    class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Jour/Semaine<span
                                        style="color:red;">*</span></label>
                                <input type="number"
                                    value="{{ old('working_days_per_week') ? old('working_days_per_week') : $item->working_days_per_week }}"
                                    name="working_days_per_week" class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Raison du fin de contrat<span
                                        style="color:red;">*</span></label>
                                <textarea type="text" name="termination_reason" class="form-control"
                                    required>{{ old('termination_reason') ? old('termination_reason') : $item->termination_reason }}</textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>