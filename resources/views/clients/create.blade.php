<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter un nouveau client</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('clients.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Nom<span
                                style="color:red;">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Addresse</label>
                        <input type="text" name="adress" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Contact</label>
                        <input type="text" name="contact" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Numéro IFU</label>
                        <input type="text" name="ifu" class="form-control">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter un nouveau médecin</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
