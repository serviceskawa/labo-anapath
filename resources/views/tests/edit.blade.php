<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modifier un examen</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('examens.update') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-body">
            
                <input type="hidden"  name="id2" id="id2" class="form-control">


                <div class="mb-3">
                    <label for="example-select" class="form-label">Catégorie</label>
                    <select class="form-select" id="category_test_id2" name="category_test_id2">
                        <option>...</option>
                        @foreach ($categories as $categorie)
                        <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                        @endforeach
            
             
                    </select>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Libellé</label>
                    <input type="text" name="name2" id="name2" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Prix</label>
                    <input type="text" name="price2" id="price2" class="form-control">
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