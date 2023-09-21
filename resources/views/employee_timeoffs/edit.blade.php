<div class="modal fade" id="bs-example-modal-lg-edit-{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Modifier ce congé</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employee.timeoff.update',$item->id) }}" method="POST" autocomplete="on"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')


                    <div class="modal-body">

                        <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="example-select" class="form-label">Nom de l'employé<span
                                        style="color:red;">*</span></label>
                                <select class="form-select form-control" name="employee_id">
                                    <option>Selectionner un employé</option>
                                    @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ $emp->id==$item->employee_id ? 'selected' :
                                        ''}}>{{ $emp->fisrt_name }} {{ $emp->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Type de congé<span
                                        style="color:red;">*</span></label>
                                <input type="text"
                                    value="{{ old('timeoff_type') ? old('timeoff_type') : $item->timeoff_type }}"
                                    name="timeoff_type" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Date de début<span
                                        style="color:red;">*</span></label>
                                <input type="date"
                                    value="{{ old('start_date') ? old('start_date') : $item->start_date }}"
                                    name="start_date" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Date de fin<span
                                        style="color:red;">*</span></label>
                                <input type="date" value="{{ old('end_date') ? old('end_date') : $item->end_date }}"
                                    name="end_date" class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Status<span
                                        style="color:red;">*</span></label>
                                <select name="status" class="form-select form-control" required>
                                    <option>Selectionner un statut</option>
                                    <option value="active" {{$item->status=="active" ? 'selected' : ''}}>Active
                                    </option>
                                    <option value="non active" {{$item->status=="non active" ? 'selected' : ''}}>Non
                                        Active</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Message<span
                                        style="color:red;">*</span></label>
                                <input type="text" value="{{ old('message') ? old('message') : $item->message }}"
                                    name="message" class="form-control" required />
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