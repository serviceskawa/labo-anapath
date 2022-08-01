<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modifier un detail</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('contrat_details.update') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-body">

                <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                <div class="mb-3">
                    <label for="example-select" class="form-label">Catégorie d'examen<span style="color:red;">*</span></label>
                    <select class="form-select" id="category_test_id2" name="category_test_id2" required>
                        <option value="">Sélectionner la catégorie</option>
                        @foreach ($test_caterories as $test_caterorie)
                        <option value="{{ $test_caterorie->id }}">{{ $test_caterorie->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Pourcentage de prise en charge<span style="color:red;">*</span></label>
                    <input type="number" name="pourcentage2" id="pourcentage2" min="0" max="100" class="form-control" required>
                </div>


                    <input type="hidden" name="contrat_id2" id="contrat_id2" class="form-control"  required>

                    <input type="hidden" name="contrat_details_id2" id="contrat_details_id2" class="form-control"  required>






            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
