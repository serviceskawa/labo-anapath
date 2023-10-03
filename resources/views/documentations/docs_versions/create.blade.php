<!-- Ajoutez une balise div pour la fenêtre modale -->
<div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Le contenu de votre fenêtre modale -->
            <div class="modal-header">
                <h5 class="modal-title" id="downloadModalLabel">Télécharger une nouvelle du document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('doc.store') }}" method="POST" autocomplete="on" enctype="multipart/form-data">
                    @csrf

                    <div style="text-align:right;"><span style="color:red;">*</span>Champs obligatoires</div>

                    <input type="hidden" name="category_id" id="category_id_input"
                        class="form-control category-id-input">

                    <div class="row">
                        <div class="mb-3 col-lg-12">
                            <label for="example-fileinput" class="form-label">Titre<span
                                    style="color:red;">*</span></label>
                            <input type="text" name="title" id="" required class="form-control">
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="example-fileinput" class="form-label">Fichier<span
                                        style="color:red;">*</span></label>
                                <input type="file" name="attachment" id="example-fileinput" required
                                    class="form-control">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Ajouter une nouvelle version</button>
                        </div>
                    </div>
                </form>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary">Télécharger</button>
            </div> --}}
        </div>
    </div>
</div>