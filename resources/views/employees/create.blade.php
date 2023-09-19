<!-- Large modal -->
<div class="modal fade" id="bs-example-modal-lg-create" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Ajouter un nouveau employé</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employee.store') }}" method="POST" autocomplete="on"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Nom<span style="color:red;">*</span></label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Prénoms<span
                                        style="color:red;">*</span></label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Date de naissance<span
                                        style="color:red;">*</span></label>
                                <input type="date" name="date_of_birth" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Lieu de naissance<span
                                        style="color:red;">*</span></label>
                                <input type="text" name="place_of_birth" class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Sexe<span
                                        style="color:red;">*</span></label>
                                <select class="form-select mb-3" name="gender" required>
                                    <option>Selectionner le sexe</option>
                                    <option value="masculin">Masculin</option>
                                    <option value="feminin">Feminin</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Nationalité<span
                                        style="color:red;">*</span></label>
                                <input type="text" name="nationality" class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Numéro CNSS<span
                                        style="color:red;">*</span></label>
                                <input type="text" name="cnss_number" class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Adresse<span
                                        style="color:red;">*</span></label>
                                <input type="text" name="address" class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Ville<span
                                        style="color:red;">*</span></label>
                                <input type="text" name="city" class="form-control" required />
                            </div>
                        </div>


                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Photo de l'employé</label>
                                <input type="file" name="photo_url" class="form-control" />
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter un nouveau employé</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>