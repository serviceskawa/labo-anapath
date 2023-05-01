<div id="modal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter une nouvelle catégorie d'examen <br>pris en compte par le contrat</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('contrat_details.store') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-body">

                <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                <div class="mb-3">
                    <label for="example-select" class="form-label">Catégorie d'examen<span style="color:red;">*</span></label>
                    <select class="form-select" id="example-select" name="category_test_id" required>
                        <option value="">Sélectionner la catégorie</option>
                        @foreach ($cateroriesTests as $cateroriesTest)
                        <option value="{{ $cateroriesTest->id }}">{{ $cateroriesTest->name }}</option>
                        @endforeach


                    </select>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Pourcentage de prise en charge<span style="color:red;">*</span></label>
                    <input type="number" name="pourcentage" class="form-control" min="0" max="100" required>
                </div>

                <input type="hidden" name="contrat_id" value="{{ $contrat->id }}" class="form-control"  required>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Ajouter une nouvelle catégorie d'examen</button>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
