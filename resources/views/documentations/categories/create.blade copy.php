<div class="card-header">
    <div class="col-12">
        <div class="page-title-box">
            Ajouter une nouvelle catégorie
        </div>
    </div>
</div>

<div class="card-body">
    <form action="{{ route('doc.categorie.store') }}" method="POST" autocomplete="on" enctype="multipart/form-data">
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