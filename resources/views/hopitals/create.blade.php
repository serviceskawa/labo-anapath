<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter un nouvel hôpital</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('hopitals.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Nom de l'hôpital<span
                                style="color:red;">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Telephone</label>
                        <input type="tel" name="telephone" class="form-control"
                            pattern="[0-9]{2}[0-9]{2}[0-9]{2}[0-9]{2}">
                        <small>Format: 97000000</small>
                    </div>


                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>


                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Adresse</label>
                        <textarea type="text" name="adresse" class="form-control"></textarea>
                    </div>


                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Commision (en pourcentage)</label>
                        <input type="number" name="commission" class="form-control" value="0" min="0"
                            max="100">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter un nouvel hôpital</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
