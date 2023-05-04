<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modifier la prestation</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('prestation.update') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <input type="hidden" name="id" id="id2" class="form-control" required>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Catégorie parente</label>
                        <select class="form-select" id="category_prestation_id2" name="category_prestation_id"
                            required>
                            <option value="">Sélectionner une catégorie</option>
                            @foreach ($categories as $categorie)
                                <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Nom de l'examen</label>
                        <input type="text" name="name" id="name2" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Prix</label>
                        <input type="text" name="price" id="price2" class="form-control" required>
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
