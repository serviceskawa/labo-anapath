<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter une nouvelle catégorie</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('supplier.categories.store') }}" method="POST">
            @csrf
            <div class="modal-body">

                <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Nom de la catégorie<span style="color:red;">*</span></label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Description<span style="color:red;">*</span></label>
                    {{-- <input type="text" name="description"> --}}
                    <textarea name="description" class="form-control" required cols="30" rows="5"></textarea>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
