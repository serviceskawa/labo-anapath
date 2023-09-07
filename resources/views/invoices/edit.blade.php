
<div id="exampleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" style="max-width: 100%; padding-left: 300px; margin-left:50px;">
        <div class="modal-content" style="width: 75%;;">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter un nouveau patient</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('patients.update') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <input type="hidden" name="id" id="id2" class="form-control">

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Code</label>
                        <input type="text" name="code" id="code2" class="form-control" readonly>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label for="simpleinput" class="form-label">Nom <span style="color:red;">*</span></label>
                            <input type="text" name="firstname" id="name2" class="form-control" required>
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label for="simpleinput" class="form-label">Prénom<span style="color:red;">*</span></label>
                            <input type="text" name="lastname" id="lastname" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label for="example-select" class="form-label">Genre<span style="color:red;">*</span></label>
                            <select class="form-select" name="genre" id="genre2">
                                <option value="">Sélectionner le genre</option>
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>

                            </select>
                            </select>
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label for="example-select" class="form-label">Langue parlée<span
                                    style="color:red;">*</span></label>
                            <select class="form-select" name="langue" id="langue2" required>
                                <option value="">Sélectionner une langue</option>
                                <option value="français">Français</option>
                                <option value="fon">Fon</option>
                                <option value="anglais">Anglais</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">

                        <div class="mb-3 col-lg-6">
                            <label for="simpleinput" class="form-label">Age<span style="color:red;">*</span></label>
                        <input type="number" name="age" id="age2" class="form-control" required>
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label for="example-select" class="form-label">Mois ou Années<span style="color:red;">*</span></label>
                        <select class="form-select" id="year_or_month2" name="year_or_month" required>
                            <option value="">Sélectionner entre mois ou ans</option>
                            <option value="0">Mois</option>
                            <option value="1">Ans</option>
                        </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-4">
                            <label for="simpleinput" class="form-label">Contact 1<span style="color:red;">*</span></label>
                        <input type="tel" name="telephone1" id="telephone1_2" class="form-control" required>
                        </div>

                        <div class="mb-3 col-lg-4">
                            <label for="simpleinput" class="form-label">Contact 2</label>
                        <input type="tel" name="telephone2" id="telephone2_2" class="form-control">
                        </div>

                        <div class="mb-3 col-lg-4">
                            <label for="simpleinput" class="form-label">Profession</label>
                        <input type="text" name="profession" id="profession2" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Adresse</label>
                        <textarea type="text" name="adresse" id="adresse2" class="form-control" required></textarea>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter un nouveau patient</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
