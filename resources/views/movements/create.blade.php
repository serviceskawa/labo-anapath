<!-- Large modal -->
<div class="modal fade" id="bs-example-modal-lg-create" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Ajouter ou diminuer le stock</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">

                <form action="{{ route('movement.store') }}" method="POST" autocomplete="off">
                    @csrf

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="row">
                        <div class="mb-3 col-lg-4">
                            <label for="example-select" class="form-label">Choisissez l'article<span
                                    style="color:red;">*</span></label>
                            <select class="form-select" id="langue" name="article_id" required>
                                <option value="">Sélectionner l'article</option>
                                @forelse ($articles as $article)
                                <option value="{{$article->id}}">{{$article->article_name}}</option>
                                @empty
                                <option value="article">Aucun article existant</option>
                                @endforelse

                            </select>
                        </div>

                        <div class="mb-3 col-lg-4">
                            <label for="example-select" class="form-label">Actions<span
                                    style="color:red;">*</span></label>
                            <select class="form-select" id="langue" name="movement_type" required>
                                <option value="">Sélectionner l'action</option>
                                <option value="ajouter">Ajouter</option>
                                <option value="diminuer">Diminuer</option>
                            </select>
                        </div>
                        <div class="mb-3 col-lg-4">
                            <label for="simpleinput" class="form-label">Quantité<span
                                    style="color:red;">*</span></label>
                            <input type="number" name="quantite_changed" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-12">
                            <label for="simpleinput" class="form-label">Description<span
                                    style="color:red;">*</span></label>
                            <textarea type="text" name="description" class="form-control" required rows="4"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter ou diminuer un stock</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->