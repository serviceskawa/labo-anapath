<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modifier les informations du client</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('clients.update') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-body">

                <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                <input type="hidden" id="id" name="id">
                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Nom<span
                            style="color:red;">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Adresse</label>
                    <input type="text" name="adress" id=adress class="form-control">
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Contact</label>
                    <input type="text" name="contact" id="contact" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Numéro IFU</label>
                    <input type="text" name="ifu" id="ifu" class="form-control">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
