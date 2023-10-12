<div class="modal fade" id="bs-example-modal-lg-create-folder" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Ajouter un nouveau ce fichier</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="card-body">
                <form action="{{ route('doc.categorie.store') }}" method="POST" autocomplete="on"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Nom<span style="color:red;">*</span></label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Ajouter une nouvelle catégorie</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>