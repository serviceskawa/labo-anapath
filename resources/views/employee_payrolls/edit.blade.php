<div class="modal fade" id="bs-example-modal-lg-edit-{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Modifier cet contrat</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employee.payroll.update',$item->id) }}" method="POST" autocomplete="on"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')


                    <div class="modal-body">

                        <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="example-select" class="form-label">Contrat employée<span
                                        style="color:red;">*</span></label>
                                <select class="form-select" name="contrat_employee_id">
                                    <option>Selectionner un contrat employée</option>
                                    @foreach ($contrats as $contrat)
                                    <option value="{{ $contrat->id }}" {{$contrat->id==$item->contrat_employee_id ?
                                        'selected' : ''}}>{{ $contrat->contract_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Salire brute/Mois<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="monthly_gross_salary"
                                    value="{{ old('monthly_gross_salary') ? old('monthly_gross_salary') : $item->monthly_gross_salary }}"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Taux brute/Heures<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="hourly_gross_rate"
                                    value="{{ old('hourly_gross_rate') ? old('hourly_gross_rate') : $item->hourly_gross_rate }}"
                                    class="form-control" required>
                            </div>
                        </div>


                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Indemnite de transport<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="transport_allowance"
                                    value="{{ old('transport_allowance') ? old('transport_allowance') : $item->transport_allowance }}"
                                    class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Iban<span
                                        style="color:red;">*</span></label>
                                <input type="text" name="iban" value="{{ old('iban') ? old('iban') : $item->iban }}"
                                    class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Bic<span style="color:red;">*</span></label>
                                <input type="text" name="bic" value="{{ old('bic') ? old('bic') : $item->bic }}"
                                    class="form-control" required />
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