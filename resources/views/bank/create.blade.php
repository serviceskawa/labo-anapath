<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter une nouvelle banque</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('bank.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Nom de la banque<span
                                style="color:red;">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Numéro de compte</label>
                        <input type="number" name="account_number" class="form-control">
                        <small>xxxxxxxxxxx</small>
                    </div>
                    {{-- pattern="[0-9]{2}[0-9]{2}[0-9]{2}[0-9]{2}" --}}

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Description<span style="color:red;">*</span></label>
                        <textarea name="description" id="description" class="form-control" required cols="30" rows="5"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter une nouvelle banque</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
