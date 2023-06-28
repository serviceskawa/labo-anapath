<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Demander un remboursement</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('refund.request.update') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-body">

                <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                <input type="hidden"  name="id" id="id2" class="form-control" required>
                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Code de la demande<span style="color:red;">*</span></label>
                    <input type="text" name="test_order_code" id="test_order_code2" class="form-control" placeholder="XX-XXXX" required>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Montant<span style="color:red;">*</span></label>
                    <input type="number" name="montant" id="montant2" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Description<span style="color:red;">*</span></label>
                    <textarea name="description" id="description2" rows="6" class="form-control" required></textarea>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Demander un remboursement</button>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
