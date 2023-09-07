<!-- Large modal -->
<div class="modal fade" id="bs-example-modal-lg-create" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Ajouter une nouvelle dépense</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('all_expense.store') }}" method="POST" autocomplete="on">
                    @csrf
                    <div class="modal-body">

                        <div style="text-align:right;"><span style="color:red;">*</span>Champs obligatoires</div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Montant<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="amount" class="form-control" required>
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Preuve</label>
                                <input type="file" name="receipt" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="example-select" class="form-label">Catégorie de dépense<span
                                        style="color:red;">*</span></label>
                                <select class="form-select" id="" name="expense_categorie_id" required>
                                    <option value="">Selectionner la catégorie de dépense</option>
                                    @foreach ($expenses_categorie as $ex_cat)
                                    <option value="{{ $ex_cat->id }}">{{ $ex_cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="example-select" class="form-label">Ticket de caisse<span
                                        style="color:red;">*</span></label>
                                <select class="form-select" id="" name="cashbox_ticket_id" required>
                                    <option value="">Selectionner le ticket de caisse</option>
                                    @foreach ($cash_ticket as $ch_ticket)
                                    <option value="{{ $ch_ticket->id }}">{{ $ch_ticket->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="example-select" class="form-label">Status<span
                                        style="color:red;">*</span></label>
                                <select class="form-select" id="" name="paid" required>
                                    <option value="">Selectionner le statut de la caisse</option>
                                    <option value="0">Non payé</option>
                                    <option value="1">Payé</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Description</label>
                                <textarea type="text" name="description" class="form-control" cols="12"
                                    rows="4"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter une nouvelle dépense</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->