{{-- <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter un nouveau patient</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form method="POST" id="createPatientForm" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Code</label>
                        <input type="text" name="code" id="code" value="<?php echo substr(md5(rand(0, 1000000)), 0, 10); ?>"
                            class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Nom <span style="color:red;">*</span></label>
                        <input type="text" name="firstname" id="firstname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Prénom<span style="color:red;">*</span></label>
                        <input type="text" name="lastname" id="lastname" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Genre<span style="color:red;">*</span></label>
                        <select class="form-select" id="genre" name="genre" required>
                            <option value="">Sélectionner le genre</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="example-date" class="form-label">Date de naissance</label>
                        <input class="form-control" id="example-date" type="date" name="birthday">
                    </div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Age<span style="color:red;">*</span></label>
                        <input type="number" name="age" id="age" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Mois ou Années<span style="color:red;">*</span></label>
                        <select class="form-select" id="year_or_month" name="year_or_month" required>
                            <option value="">Sélectionner entre mois ou ans</option>
                            <option value="0">Mois</option>
                            <option value="1">Ans</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Profession</label>
                        <input type="text" name="profession" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Contact 1<span style="color:red;">*</span></label>
                        <input type="tel" name="telephone1" id="telephone1" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Contact 2</label>
                        <input type="tel" name="telephone2" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Adresse<span style="color:red;">*</span></label>
                        <textarea type="text" name="adresse" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Langue parlée<span style="color:red;">*</span></label>
                        <select class="form-select" id="langue" name="langue" required>
                            <option value="">Sélectionner une langue</option>
                            <option value="français">Français</option>
                            <option value="fon">Fon</option>
                            <option value="anglais">Anglais</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter un nouveau patient</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --> --}}

<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog" style="max-width: 100%; padding-left: 300px; margin-left:50px;">
        <div class="modal-content" style="width: 75%;;">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter un nouveau patient</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('patients.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Code</label>
                        <input type="text" name="code" value="<?php echo substr(md5(rand(0, 1000000)), 0, 10); ?>" class="form-control" readonly>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label for="simpleinput" class="form-label">Nom <span style="color:red;">*</span></label>
                            <input type="text" name="firstname" class="form-control" required>
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label for="simpleinput" class="form-label">Prénom<span style="color:red;">*</span></label>
                            <input type="text" name="lastname" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label for="example-select" class="form-label">Genre<span
                                    style="color:red;">*</span></label>
                            <select class="form-select" id="example-select" name="genre" required>
                                <option value="">Sélectionner le genre</option>
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label for="example-select" class="form-label">Langue parlée<span
                                    style="color:red;">*</span></label>
                            <select class="form-select" id="langue" name="langue" required>
                                <option value="">Sélectionner une langue</option>
                                <option value="français">Français</option>
                                <option value="fon">Fon</option>
                                <option value="anglais">Anglais</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-4">
                            <label for="example-date" class="form-label">Date de naissance</label>
                            <input class="form-control" id="example-date" type="date" name="birthday">
                        </div>

                        <div class="mb-3 col-lg-4">
                            <label for="simpleinput" class="form-label">Age<span style="color:red;">*</span></label>
                            <input type="number" name="age" class="form-control" required>
                        </div>
                        <div class="mb-3 col-lg-4">
                            <label for="example-select" class="form-label">Mois ou Années<span
                                    style="color:red;">*</span></label>
                            <select class="form-select" id="year_or_month" name="year_or_month" required>
                                <option value="">Sélectionner entre mois ou ans</option>
                                <option value="0">Mois</option>
                                <option value="1">Ans</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-4">
                            <label for="simpleinput" class="form-label">Contact 1<span
                                    style="color:red;">*</span></label>
                            <input type="tel" name="telephone1" class="form-control" required>
                        </div>

                        <div class="mb-3 col-lg-4">
                            <label for="simpleinput" class="form-label">Contact 2</label>
                            <input type="tel" name="telephone2" class="form-control">
                        </div>

                        <div class="mb-3 col-lg-4">
                            <label for="simpleinput" class="form-label">Profession</label>
                            <input type="text" name="profession" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Adresse<span style="color:red;">*</span></label>
                        <textarea type="text" name="adresse" class="form-control" required></textarea>
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
