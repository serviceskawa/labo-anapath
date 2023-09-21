<!-- Large modal -->
<div class="modal fade" id="bs-example-modal-lg-create" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Ajouter un contrat employé</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employee.payroll.store') }}" method="POST" autocomplete="on"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="example-select" class="form-label">Contrat employée<span
                                        style="color:red;">*</span></label>
                                <select class="form-select" name="contrat_employee_id">
                                    <option>Selectionner un contrat employée</option>
                                    @foreach ($contrats as $contrat)
                                    <option value="{{ $contrat->id }}">{{ $contrat->contract_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Salire brute/Mois<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="monthly_gross_salary" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Taux brute/Heures<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="hourly_gross_rate" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Indemnite de transport<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="transport_allowance" class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Iban<span
                                        style="color:red;">*</span></label>
                                <input type="text" name="iban" class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Bic<span style="color:red;">*</span></label>
                                <input type="text" name="bic" class="form-control" required />
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