<!-- Large modal -->
<div class="modal fade" id="bs-example-modal-lg-create" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Creer un article</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('article.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="modal-body">

                        <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Nom de l'article</article><span
                                        style="color:red;">*</span></label>
                                <input type="text" name="article_name" class="form-control" required>
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Description<span
                                        style="color:red;">*</span></label>
                                <textarea type="text" name="description" class="form-control" cols="6" rows="3"
                                    required></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Quantité en stock<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="quantity_in_stock" class="form-control" required>
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="example-select" class="form-label">Unité de mesure<span
                                        style="color:red;">*</span></label>
                                <select class="form-select" id="langue" name="unit_of_measurement" required>
                                    <option value="">Sélectionner l'unité de mesure de la quantité</option>
                                    <option value="litre">Litre</option>
                                    <option value="bouteille">Bouteille</option>
                                    <option value="kilogramme">Kilogramme</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label for="example-date" class="form-label">Date d'expiration</label>
                                <input class="form-control" id="example-date" type="date" name="expiration_date">
                            </div>

                            <div class="mb-3 col-lg-4">
                                <label for="simpleinput" class="form-label">Numéro du lot<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="lot_number" class="form-control" required>
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label for="simpleinput" class="form-label">Minumum<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="minimum" class="form-control" required>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter un nouveau article</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->