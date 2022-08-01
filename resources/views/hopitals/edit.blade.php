<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modifier les informations de l'hôpital</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('hopitals.update') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-body">

                <input type="hidden"  name="id2" id="id2" class="form-control">

                <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Nom de l'hôpital<span style="color:red;">*</span></label>
                    <input type="text" name="name2" id="name2" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Telephone<span style="color:red;">*</span></label>
                    <input type="tel" name="telephone2" id="telephone2" pattern="[0-9]{2}[0-9]{2}[0-9]{2}[0-9]{2}" class="form-control" required>
                    <small>Format: 97000000</small>
                </div>


                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Email</label>
                    <input type="email" name="email2" id="email2" class="form-control">
                </div>


                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Adresse<span style="color:red;">*</span></label>
                    <textarea name="adresse2" id="adresse2" class="form-control" type="text" required></textarea>
                </div>


                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Commision (en pourcentage)<span style="color:red;">*</span></label>
                    <input type="number" name="commission2" id="commission2" class="form-control" required>
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
