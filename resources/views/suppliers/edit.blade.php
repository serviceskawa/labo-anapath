<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter un nouveau médecin</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('supplier.update')}}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                    <input type="hidden" name="id" id="id" class="form-control" required>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Nom & Prénom<span
                                style="color:red;">*</span></label>
                        <input type="text" name="name" id="name2" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Telephone</label>
                        <input type="tel" name="phone" id="phone2" class="form-control"
                            pattern="[0-9]{2}[0-9]{2}[0-9]{2}[0-9]{2}">
                        <small>Format: 97000000</small>
                    </div>


                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Email</label>
                        <input type="email" name="email" id="email2" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Addresse</label>
                        <input type="text" name="address" id="address2" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Catégorie fournisseur</label>
                        <select class="form-select" id="supplier_category_id2" name="supplier_category_id" required>
                            <option value="">Sélectionner une catégorie</option>
                            @foreach ($categories as $categorie)
                            <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Contact information<span style="color:red;">*</span></label>
                        <textarea name="information" id="information2" class="form-control" required cols="30" rows="5"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter un nouveau fournisseur</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
