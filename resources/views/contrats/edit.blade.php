<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter un contrat</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('contrats.update') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-body">
            
           

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Libellé</label>
                    <input type="text" name="name2" id="name2" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="example-select" class="form-label">Type</label>
                    <select class="form-select" id="type2" name="type2" required>
                        <option>...</option>

                        <option value="ORDINAIRE">ORDINAIRE</option>
                        <option value="ASSURANCE">ASSURANCE</option>

                    </select>
                </div>

                <input type="hidden" id="id2" name="id2">

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Description</label>
                    <input type="text" name="description2" id="description2" class="form-control"  required>
                </div>

            

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->