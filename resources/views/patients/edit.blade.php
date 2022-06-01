<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modifier un patient</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('patients.update') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-body">

                <input type="hidden"  name="id2" id="id2" class="form-control">




                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Code</label>
                    <input type="text" name="code2" id="code2" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Nom & Prénom</label>
                    <input type="text" name="name2" id="name2" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Age</label>
                    <input type="number" name="age2" id="age2" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Profession</label>
                    <input type="text" name="profession2" id="profession2" class="form-control" >
                </div>

                <div class="mb-3">
                    <label for="example-select" class="form-label">Genre</label>
                    <select class="form-select"  name="genre2" id="genre2">
                        <option>...</option>

                        <option value="HOMME">HOMME</option>
                        <option value="FEMME">FEMME</option>

                    </select>
                </div>


                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Contact 1</label>
                    <input type="text" name="telephone1_2" id="telephone1_2" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Contact 2</label>
                    <input type="text" name="telephone2_2" id="telephone2_2" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Adresse</label>
                    <input type="text" name="adresse2" id="adresse2" class="form-control">
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
