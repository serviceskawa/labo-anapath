<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter un examen</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('examens.store') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-body">
            
                <div class="mb-3">
                    <label for="example-select" class="form-label">Catégorie</label>
                    <select class="form-select" id="example-select" name="category_test_id">
                        <option>...</option>
                        @foreach ($categories as $categorie)
                        <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                        @endforeach
            
             
                    </select>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Libellé</label>
                    <input type="text" name="name" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Prix</label>
                    <input type="text" name="price" class="form-control">
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