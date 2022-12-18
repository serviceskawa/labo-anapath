<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modifier les informations du contrat</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('contrats.update') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Nom du contrat<span
                                style="color:red;">*</span></label>
                        <input type="text" name="name2" id="name2" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Type<span style="color:red;">*</span></label>

                        <select class="form-select" id="type2" name="type2" required>
                            <option value="">Sélectionner le type de contrat</option>
                            <option value="ORDINAIRE">Ordinaire</option>
                            <option value="ASSURANCE">Assurance</option>
                            <option value="CAMPAGNE">Campagne</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Statut<span style="color:red;">*</span></label>

                        <select class="form-select" id="status2" name="status2" required>
                            <option value="">Sélectionner le statut</option>
                            <option value="ACTIF">Actif</option>
                            <option value="INACTIF">Inactif</option>

                        </select>
                    </div>

                    <input type="hidden" id="id2" name="id2">
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Nombre d'examens<span
                                style="color:red;">*</span></label>
                        <input type="number" min="-1" name="nbr_examen" value="-1" class="form-control" id="nbr_examen"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Description<span style="color:red;">*</span></label>
                        <textarea type="text" name="description2" id="description2" class="form-control"
                            required></textarea>
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