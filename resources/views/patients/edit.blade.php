<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modifier les informations du patient</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('patients.update') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <input type="hidden" name="id2" id="id2" class="form-control">

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Code</label>
                        <input type="text" name="code2" id="code2" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Nom <span style="color:red;">*</span></label>
                        <input type="text" name="firstname2" id="name2" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Prénom<span style="color:red;">*</span></label>
                        <input type="text" name="lastname2" id="lastname" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Age<span style="color:red;">*</span></label>
                        <input type="number" name="age2" id="age2" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Profession</label>
                        <input type="text" name="profession2" id="profession2" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Genre<span style="color:red;">*</span></label>
                        <select class="form-select" name="genre2" id="genre2">
                            <option value="">Sélectionner le genre</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>

                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Contact 1<span style="color:red;">*</span></label>
                        <input type="tel" name="telephone1_2" id="telephone1_2" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Contact 2</label>
                        <input type="tel" name="telephone2_2" id="telephone2_2" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Adresse</label>
                        <textarea type="text" name="adresse2" id="adresse2" class="form-control" required></textarea>
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
