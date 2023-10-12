<div class="card-header">
    <div class="col-12">
        <div class="page-title-box">
            Ajouter un nouveau fichier
        </div>
    </div>
</div>

<div class="card-body">
    <form action="{{ route('doc.store') }}" method="POST" autocomplete="on" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <div style="text-align:right;"><span style="color:red;">*</span>Champs obligatoires</div>

            <div class="row">
                <div class="mb-3 col-lg-12">
                    <label for="simpleinput" class="form-label">Nom<span style="color:red;">*</span></label>
                    <input type="text" name="title" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-lg-12">
                    <label for="simpleinput" class="form-label">Type de catégorie</label>
                    <select class="form-control select2" name="documentation_categorie_id" data-toggle="select2">
                        <option>Selectionner la catégorie</option>
                        @foreach ($select_categorie as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-lg-12">
                    <label for="simpleinput" class="form-label">Description</label><br>
                    <textarea name="description" class="form-control" id="example-textarea" rows="5"></textarea>
                </div>
            </div>


            <div class="row">
                <div class="mb-3 col-lg-12">
                    <label for="example-fileinput" class="form-label">Fichier<span style="color:red;">*</span></label>
                    <input type="file" name="attachment" id="example-fileinput" class="form-control">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Ajouter un nouveau fichier</button>
        </div>
    </form>
</div>