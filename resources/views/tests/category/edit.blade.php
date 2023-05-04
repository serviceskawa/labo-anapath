<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modifier la catégorie d'examen</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('examens.categories.update') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-body">

                <input type="hidden" name="id" id="id2" class="form-control" required>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Code<span style="color:red;">*</span></label>
                    <input type="text" name="code"  id="code2" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Nom de la catégorie d'examen<span style="color:red;">*</span></label>
                    <input type="text" name="name" id="name2" class="form-control" required>
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
